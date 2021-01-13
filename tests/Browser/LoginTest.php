<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;

/**
 * Login tests.
 */
class LoginTest extends DuskTestCase
{
  use DatabaseMigrations;

    /**
     * Tests login with invalid credentials.
     *
     * @throws \Throwable
     *
     * @return void
     */
    public function testUserLoginWithInValidCredentials(): void
    {
        factory(User::class)->create([
            'email' => 'user@user.com'
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Returning Customer')
                ->type('email', 'user@user.com')
                ->type('password', 'wrong-password')
                ->press('Login')
                ->assertSee('These credentials do not match our records.')
                ->assertPathIs('/login');
        });
    }

    /**
     * Tests login with valid credentials.
     *
     * @throws \Throwable
     *
     * @return void
     */
    public function testUserLoginWithValidCredentials(): void
    {
        factory(User::class)->create([
            'email' => 'user@user.com'
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->visit('/login')
                ->assertSee('Returning Customer')
                ->type('email', 'user@user.com')
                ->type('password', 'password')
                ->press('Login')
                ->assertPathIs('/');
        });
    }

}
