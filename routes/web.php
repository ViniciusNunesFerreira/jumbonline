<?php

use Illuminate\Support\Facades\Route;
use App\Models\Cart;

use MercadoPago\MercadoPagoConfig;
use App\Models\PaymentMethod;
use MercadoPago\Client\Payment\PaymentClient;
use Illuminate\Http\Request;
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

Route::group(['as' => 'guest.', 'middleware' => \App\Http\Middleware\RedirectIfNotSetup::class], function () {
    Route::get('/', \App\Http\Livewire\Guest\Welcome::class)->name('welcome');
    Route::get('/collections', \App\Http\Livewire\Guest\CollectionList::class)->name('collections.list');
    Route::get('/collections/{collection}', \App\Http\Livewire\Guest\CollectionDetail::class)->name('collections.detail');
    Route::redirect('/checkout', '/purchase')->name('checkout');
    Route::get('/products/{prison:slug}', \App\Http\Livewire\Guest\ProductList::class)->name('products.list');
    Route::get('/products/{product}', \App\Http\Livewire\Guest\ProductDetail::class)->name('products.detail');
    Route::get('/cart', \App\Http\Livewire\Guest\ShoppingCart::class)->name('cart');
   // Route::get('/checkout', \App\Http\Livewire\Guest\Checkout::class)->name('checkout');
    Route::get('/purchase', \App\Http\Livewire\Guest\Purchase::class)->name('purchase');
    Route::get('/order/{order}/payment/', \App\Http\Livewire\Guest\OrderPayment::class)->name('order.payment');
    Route::get('/orders/{order}', \App\Http\Livewire\Guest\OrderDetail::class)->name('orders.detail')->middleware('signed');
    Route::get('/blog', \App\Http\Livewire\Guest\Blog\ArticleList::class)->name('blog.articles.list');
    Route::get('/blog/{article:slug}', \App\Http\Livewire\Guest\Blog\ArticleDetail::class)->name('blog.articles.detail');
    Route::get('/blog/tag/{tag:slug}', \App\Http\Livewire\Guest\Blog\TagDetail::class)->name('blog.tags.detail');
    Route::get('/contact', \App\Http\Livewire\Guest\Contact::class)->name('contact');
    Route::get('/pages/{page:slug}', \App\Http\Livewire\Guest\PageDetail::class)->name('pages.detail');
});


//Route::get('/setup', \App\Http\Livewire\Setup\Setup::class)->middleware(\App\Http\Middleware\RedirectIfSetupFinished::class)->name('setup');


Route::webhooks('webhooks/mercadopago', 'mercadopago');
Route::stripeWebhooks('webhooks/stripe');

require __DIR__ . '/auth.php';
require __DIR__ . '/customer.php';
require __DIR__ . '/employee.php';
