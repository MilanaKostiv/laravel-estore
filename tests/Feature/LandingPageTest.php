<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Product;

/**
 * Landing Page Tests.
 */
class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests the landing page rendering.
     *
     * @return void
     */
    public function testLandingPageRendering(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Laravel eStore');
    }

    /**
     * Tests featured product is displayed.
     *
     * @return void
     */
    public function testFeaturedProductDisplayed(): void
    {
        $featuredProduct = factory(Product::class)->create([
            'featured' => true
        ]);

        $response = $this->get('/');
        $response->assertSee($featuredProduct->name);
    }

    /**
     * Tests non-featured product is not displayed.
     *
     * @return void
     */
    public function testNonFeaturedProductNotDisplayed(): void
    {
        $notFeaturedProduct = factory(Product::class)->create();

        $response = $this->get('/');
        $response->assertDontSee($notFeaturedProduct->name);
    }
}
