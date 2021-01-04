<?php

namespace App\Providers;

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
