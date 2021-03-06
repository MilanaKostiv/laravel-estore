<?php

namespace Tests\Feature;

use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Shop page tests.
 */
class ShopPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests shop page rendering.
     *
     * @return void
     */
    public function testShopPageRendering(): void
    {
        $response = $this->get('/shop');
        $response->assertStatus(200);
        $response->assertSee('Featured');
    }

    /**
     * Tests the featured product
     *
     * @return void
     */
    public function testFeaturedProductDisplayed(): void
    {
        $featuredProduct = factory(Product::class)->create([
            'featured' => true
        ]);

        $response = $this->get('/shop');
        $response->assertSee($featuredProduct->name);
    }

    /**
     * Tests the non-featured product is not rendered.
     *
     * @return void
     */
    public function testNotFeaturedProductNotDisplayed(): void
    {
        $notFeaturedProduct = factory(Product::class)->create();

        $response = $this->get('/shop');
        $response->assertDontSee($notFeaturedProduct->name);
    }

    /**
     * Tests pagination
     *
     * @return void
     */
    public function testPaginationForProducts(): void
    {
        for ($i=1; $i<10; $i++) {
             factory(Product::class)->create([
                'featured' => true,
                'name' => 'Product '. $i
            ]);
        }
        $response = $this->get('/shop');

        //Test the first page
        $response->assertSee('Product 2');
        $response->assertSee('Product 9');
        $response->assertDontSee('Product 10');

    }

    /**
     * Tests the products are sorted by price ASC
     *
     * @return void
     */
    public function testLowToHighPriceFilter(): void
    {
        factory(Product::class)->create([
            'featured' => true,
            'name' => 'Product Middle',
            'price' => 1500
        ]);
        factory(Product::class)->create([
            'featured' => true,
            'name' => 'Product Low',
            'price' => 1000
        ]);
        factory(Product::class)->create([
            'featured' => true,
            'name' => 'Product High',
            'price' => 2000
        ]);

        $response = $this->get('/shop?sort=low_high');
        $response->assertSeeInOrder(['Product Low', 'Product Middle', 'Product High']);
    }

    /**
     * Tests the products are sorted by price DESC
     *
     * @return void
     */
    public function testHighToLowPriceFilter(): void
    {
        factory(Product::class)->create([
            'featured' => true,
            'name' => 'Product Middle',
            'price' => 1500
        ]);
        factory(Product::class)->create([
            'featured' => true,
            'name' => 'Product Low',
            'price' => 1000
        ]);
        factory(Product::class)->create([
            'featured' => true,
            'name' => 'Product High',
            'price' => 2000
        ]);

        $response = $this->get('/shop?sort=high_low');
        $response->assertSeeInOrder(['Product High', 'Product Middle', 'Product Low']);
    }
}
