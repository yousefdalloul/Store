<?php

use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Front\Auth\TwoFactorAuthenticationController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckOutController;
use App\Http\Controllers\Front\CurrencyConverterController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\PaymentsController;
use App\Http\Controllers\front\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripeWebhooksController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
], function() {

    Route::get('/', [HomeController::class, 'index'])
        ->name('home');

    Route::get('/products', [ProductsController::class, 'index'])
        ->name('products.index');

    Route::get('/products/{product:slug}', [ProductsController::class, 'show'])
        ->name('products.show');

    Route::resource('cart', CartController::class);

    Route::get('checkout', [CheckoutController::class, 'create'])->name('checkout');
    Route::post('checkout', [CheckoutController::class, 'store']);


    Route::get('auth/user/2fa', [TwoFactorAuthenticationController::class, 'index'])
        ->name('front.2fa');

    Route::post('currency', [CurrencyConverterController::class, 'store'])
        ->name('currency.store');

//    Route::post('checkout/create-payment', [PaymentsController::class, 'store'])
//        ->name('checkout.payment');

});

Route::get('auth/{provider}/redirect',[SocialLoginController::class,'redirect'])
    ->name('auth.provider.redirect');

Route::get('auth/{provider}/callback',[SocialLoginController::class,'callback'])
    ->name('auth.provider.callback');

//Route::get('auth/{provider}/callback',[SocialLoginController::class,'index']);

Route::get('orders/{order}/pay', [PaymentsController::class, 'create'])
    ->name('orders.payments.create');

Route::post('orders/{order}/stripe/payment-intent', [PaymentsController::class, 'createStripePaymentIntent'])
    ->name('stripe.paymentIntent.create');

Route::get('orders/{order}/pay/stripe/callback', [PaymentsController::class, 'confirm'])
    ->name('stripe.return');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::any('stripe/webhook',[StripeWebhooksController::class,'handel']);

//require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';
