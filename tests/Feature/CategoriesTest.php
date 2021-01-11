<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Product;
use App\Category;

/**
 * Categories tests.
 */
class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests the products are displayed in their categories.
     * @return void
     */
    public function testCategoryPageShowsCorrectProducts(): void
    {
        $laptop1 = factory(Product::class)->create([
            'name' => 'Laptop 1'
        ]);

        $laptop2 = factory(Product::class)->create([
            'name' => 'Laptop 2'
        ]);

        $desktop = factory(Product::class)->create([
            'name' => 'Desktop 1'
        ]);

        $laptopsCategory = Category::create([
            'name' => 'laptops',
            'slug' => 'laptops'
        ]);

        $desktopsCategory = Category::create([
            'name' => 'desktops',
            'slug' => 'desktops'
        ]);

        $laptop1->categories()->attach($laptopsCategory->id);
        $laptop2->categories()->attach($laptopsCategory->id);
        $desktop->categories()->attach($desktopsCategory->id);

        // Check Laptops Category
        $response = $this->get('/shop?category=laptops');
        $response->assertSee('Laptop 1');
        $response->assertSee('Laptop 2');
        $response->assertDontSee('Desktop 1');

        // Check Desktop Category
        $response = $this->get('/shop?category=desktops');
        $response->assertDontSee('Laptop 1');
        $response->assertDontSee('Laptop 2');
        $response->assertSee('Desktop 1');

    }
}
