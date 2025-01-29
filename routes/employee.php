<?php

use App\Http\Livewire\Employee\Auth\ForgotPassword;
use App\Http\Livewire\Employee\Auth\Login;
use App\Http\Livewire\Employee\Auth\ResetPassword;
use App\Http\Middleware\RedirectIfNotSetup;
use Illuminate\Support\Facades\Route;


Route::group([
    'prefix' => config('app.admin_path'),
    'as' => 'employee.'
], function () {
    Route::group(['middleware' => 'guest:employee'], function () {
        Route::get('/login', Login::class)->name('login');
        Route::get('/forgot-password', ForgotPassword::class)->name('forgot-password');
        Route::get('/reset-password/{token}', ResetPassword::class)->name('reset-password');
    });

    Route::group(['middleware' => 'auth:employee'], function () {
        Route::get('/', \App\Http\Livewire\Employee\Dashboard::class)->name('dashboard');
        Route::get('/profile', \App\Http\Livewire\Employee\Profile\ProfileManager::class)->name('profile');
        Route::get('/orders', \App\Http\Livewire\Employee\Order\OrderList::class)->name('orders.list');
        Route::get('/orders/{order:id}', \App\Http\Livewire\Employee\Order\OrderDetail::class)->name('orders.detail');
        Route::get('/orders/{order}/refund', \App\Http\Livewire\Employee\Order\OrderRefundCreate::class)->name('orders.refund');
        Route::get('/orders/{order}/shipments/create', \App\Http\Livewire\Employee\Order\OrderShipmentCreate::class)->name('orders.shipments.create');
        Route::get('/orders/{order}/shipments/label', \App\Http\Livewire\Employee\Shipping\ShippingPrePost::class)->name('orders.shipments.label');
        Route::get('/collections', \App\Http\Livewire\Employee\Collection\CollectionList::class)->name('collections.list');
        Route::get('/collections/create', \App\Http\Livewire\Employee\Collection\CollectionDetail::class)->name('collections.create');
        Route::get('/collections/{collection:id}', \App\Http\Livewire\Employee\Collection\CollectionDetail::class)->name('collections.detail');
        Route::get('/categories', \App\Http\Livewire\Employee\Category\CategoryList::class)->name('categories.list');
        Route::get('/categories/create', \App\Http\Livewire\Employee\Category\CategoryDetail::class)->name('categories.create');
        Route::get('/categories/{category:id}', \App\Http\Livewire\Employee\Category\CategoryDetail::class)->name('categories.detail');
        Route::get('/products', \App\Http\Livewire\Employee\Product\ProductList::class)->name('products.list');
        Route::get('/products/{product:id}', \App\Http\Livewire\Employee\Product\ProductDetail::class)->name('products.detail');
        Route::get('/products/{product:id}/variants/{variant:id}', \App\Http\Livewire\Employee\Product\ProductVariantDetail::class)->name('products.variants.detail');
        Route::get('/reviews', \App\Http\Livewire\Employee\Review\ReviewList::class)->name('reviews.list');
        Route::get('/reviews/create', \App\Http\Livewire\Employee\Review\ReviewCreate::class)->name('reviews.create');
        Route::get('/reviews/{review:id}', \App\Http\Livewire\Employee\Review\ReviewDetail::class)->name('reviews.detail');
        Route::get('/customers', \App\Http\Livewire\Employee\Customer\CustomerList::class)->name('customers.list');
        Route::get('/customers/create', \App\Http\Livewire\Employee\Customer\CustomerCreate::class)->name('customers.create');
        Route::get('/customers/{customer:id}', \App\Http\Livewire\Employee\Customer\CustomerDetail::class)->name('customers.detail');
        Route::get('/discounts', \App\Http\Livewire\Employee\Discount\DiscountList::class)->name('discounts.list');
        Route::get('/discounts/create', \App\Http\Livewire\Employee\Discount\DiscountDetail::class)->name('discounts.create');
        Route::get('/discounts/{discount:id}', \App\Http\Livewire\Employee\Discount\DiscountDetail::class)->name('discounts.detail');
        Route::get('/promotions', \App\Http\Livewire\Employee\Promotion\PromotionManager::class)->name('promotions.list');
        Route::get('/shipping', \App\Http\Livewire\Employee\Shipping\ShippingMethods::class)->name('shipping.manager');
        Route::get('/taxes', \App\Http\Livewire\Employee\Taxation\TaxManager::class)->name('taxes.manager');
        Route::get('/articles', \App\Http\Livewire\Employee\Article\ArticleList::class)->name('articles.list');
        Route::get('/articles/{article:id}', \App\Http\Livewire\Employee\Article\ArticleDetail::class)->name('articles.detail');
        Route::get('/pages', \App\Http\Livewire\Employee\Page\PageList::class)->name('pages.list');
        Route::get('/pages/{page:id}', \App\Http\Livewire\Employee\Page\PageDetail::class)->name('pages.detail');
        Route::get('/prisons', \App\Http\Livewire\Employee\PrisonUnid\PrisonList::class)->name('prison.list');
        Route::get('/prisons/create', \App\Http\Livewire\Employee\PrisonUnid\PrisonCreate::class)->name('prison.create');
        Route::get('/prisons/{prison:id}', \App\Http\Livewire\Employee\PrisonUnid\PrisonDetail::class)->name('prison.detail');
        Route::redirect('/settings', '/admin/settings/general');
        Route::get('/settings/general', \App\Http\Livewire\Employee\Settings\GeneralSettingManager::class)->name('settings.general');
        Route::get('/settings/user', \App\Http\Livewire\Employee\User\UserList::class)->name('settings.user.list');
        Route::get('/settings/user/create', \App\Http\Livewire\Employee\User\UserCreate::class)->name('settings.user.create');
        Route::get('/settings/user/{user:id}', \App\Http\Livewire\Employee\User\UserDetail::class)->name('settings.user.detail');
        Route::get('/settings/branding', \App\Http\Livewire\Employee\Settings\BrandSettingManager::class)->name('settings.branding');
        Route::get('/settings/payments', \App\Http\Livewire\Employee\Settings\PaymentSettingManager::class)->name('settings.payments');
        Route::get('/settings/navigation', \App\Http\Livewire\Employee\Settings\NavigationSettingManager::class)->name('settings.navigation');
        Route::get('/settings/carousels', \App\Http\Livewire\Employee\Carousel\CarouselList::class)->name('settings.carousels.list');
        Route::get('/settings/carousels/{carousel:id}', \App\Http\Livewire\Employee\Carousel\CarouselDetail::class)->name('settings.carousels.detail');
        Route::get('/settings/layout', \App\Http\Livewire\Employee\Settings\LayoutSettingManager::class)->name('settings.layout');
        Route::get('/settings/template', \App\Http\Livewire\Employee\Settings\TemplateSettingManager::class)->name('settings.template');
        Route::get('/settings/checkout', \App\Http\Livewire\Employee\Settings\CheckoutSettingManager::class)->name('settings.checkout');
        Route::get('/settings/license', \App\Http\Livewire\Employee\Settings\LicenseSettingManager::class)->name('settings.license');
    });
});
