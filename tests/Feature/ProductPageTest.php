<?php

namespace Tests\Feature;

use App\Product;
use App\Services\Product\ProductPriceFormatter;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Product page tests.
 */
class ProductPageTest extends TestCase
{
   use RefreshDatabase;

    /**
     * @var ProductPriceFormatter
     */
    private $productPriceFormatter;

    /**
     * {@inheritDoc}
     *
     * @throws BindingResolutionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->productPriceFormatter = $this->app->make(ProductPriceFormatter::class);
    }

    /**
     * Tests the product rendering
     *
     * @return void
     */
    public function testProductDisplayedCorrectly(): void
    {
        $product = factory(Product::class)->create([
            'name' => 'Laptop 1',
            'slug' => 'laptop 1',
            'details' => '15 inch, 2 TB SSD, 32GB RAM',
            'price' => 2499,
            'description' => 'Description for Laptop 1'
        ]);
        $product = $this->productPriceFormatter->addFormattedPriceToProduct($product);

        $response = $this->get('/shop/'. $product->slug);
        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($product->details);
        $response->assertSee($product->formattedPrice);
        $response->assertSee($product->description);
    }

    /**
     * Tests product 'In Stock' status.
     *
     * @return void
     */
    public function testInStock(): void
    {
        $product = factory(Product::class)->create();
        $response = $this->get('/shop/'. $product->slug);

        $response->assertStatus(200);
        $response->assertSee('In Stock');
    }

    /**
     * Tests product 'Out Of Stock' status.
     *
     * @return void
     */
    public function testOutOfStuck(): void
    {
        $product = factory(Product::class)->create([
            'quantity' => 0
        ]);
        $response = $this->get('/shop/'. $product->slug);

        $response->assertStatus(200);
        $response->assertSee('Out Of Stock');
    }

    /**
     * Tests 'You might also like' is displayed on the product page.
     *
     * @return void
     */
    public function testYouMightAlsoLikeIsVisible(): void
    {
        $product1 = factory(Product::class)->create([
            'name' => 'Product 1'
        ]);
        factory(Product::class)->create([
            'name' => 'Product 2'
        ]);
        $response = $this->get('/shop/'. $product1->slug);

        $response->assertSee('Product 2');
        $response->assertViewHas('mightAlsoLike');
    }
}
