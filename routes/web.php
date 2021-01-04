<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'LandingController@index')->name('landing.index');
Route::get('/shop', 'ShopController@index')->name('shop.index');
Route::get('/shop/{product}', 'ShopController@show')->name('shop.show');

//Cart routes
Route::post('/cart/{product}', 'CartController@addToCart')->name('cart.addToCart');
Route::get('/cart', 'CartController@index')->name('cart.index');
Route::delete('/cart/destroy/{id}', 'CartController@destroy')->name('cart.destroy');
Route::patch('/cart/{id}', 'CartController@update')->name('cart.update');

//SaveForLater routes
Route::post('/saveforlater/{id}','SaveForLaterController@store')->name('saveforlater.store');
Route::delete('/saveforlater/{id}', 'SaveForLaterController@destroy')->name('saveforlater.destroy');
Route::post('/saveforlater/movetocart/{id}', 'SaveForLaterController@moveToCart')->name('saveforlater.movetocart');

Route::get('/checkout', 'CheckoutController@index')->name('checkout.index')->middleware('auth');
Route::post('/checkout', 'CheckoutController@makeOrder')->name('checkout.makeorder');
Route::get('/guestCheckout', 'CheckoutController@index')->name('guestCheckout.index');
Route::get('/thankyou', 'ConfirmationController@index')->name('confirmation.index');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
