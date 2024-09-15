<?php

use App\Http\Middleware\RedirectIfNotSetup;
use Illuminate\Support\Facades\Route;


Route::group([
    'prefix' => 'account',
    'as' => 'customer.',
    'middleware' => 'cors',
], function () {

    Route::group(['middleware' => 'auth:customer'], function(){
        Route::get('/profile', \App\Http\Livewire\Customer\Profile\ProfileManager::class)->name('profile');
        Route::get('/orders', \App\Http\Livewire\Customer\Order\OrderList::class)->name('orders.list');
        Route::get('/orders/{order}', \App\Http\Livewire\Customer\Order\OrderDetail::class)->name('orders.detail');
        Route::get('/order/{order}/payment/', \App\Http\Livewire\Customer\OrderPayment::class)->name('order.payment');
        Route::post('/order/{order}/payment', [\App\Http\Livewire\Customer\OrderPayment::class, 'createPaymentOrder'])->name('purchase.post');
        
    });
    
});
