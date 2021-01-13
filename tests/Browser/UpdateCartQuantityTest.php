<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Product;

/**
 * Update Cart Quantity test.
 */
class UpdateCartQuantityTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Tests the quantity is updated in the shopping cart.
     *
     * @throws \Throwable
     *
     * @return void
     */
    public function testCartItemCanUpdateQuantity(): void
    {
        factory(Product::class)->create([
            'name' => 'Laptop 1',
            'slug' => 'laptop-1'
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/shop/laptop-1')
                    ->assertSee('Laptop 1')
                    ->press('Add to Cart')
                    ->assertPathIs('/cart')
                    ->click('.plus')
                    ->select('.quantity', 2);
        });
    }
}
