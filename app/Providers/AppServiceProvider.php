<?php

namespace App\Providers;
use Laravel\Dusk\DuskServiceProvider;

use App\Services\Payment\PaymentFactory;
use App\Services\Payment\PaymentFactoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Services\Checkout;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(Checkout::class)
            ->needs(PaymentFactoryInterface::class)
            ->give(function () {
                return new PaymentFactory();
            });

        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
