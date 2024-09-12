<?php

namespace App\Providers;

use App\Settings\BrandSetting;
use App\Settings\GeneralSetting;
use App\Settings\LayoutSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Component;
use Livewire\Livewire;
use App\Observers\CartItemObserver;
use App\Observers\CartObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            \DB::connection()->getPdo();

            \App\Models\Cart::observe(CartObserver::class);
            \App\Models\CartItem::observe(CartItemObserver::class);

            
            $general_settings = app(GeneralSetting::class);

            config([
                'seotools.meta.defaults.title' => $general_settings->store_name,
                'seotools.opengraph.defaults.title' => $general_settings->store_name,
                'seotools.opengraph.defaults.site_name' => $general_settings->store_name,
                'seotools.json-ld.defaults.title' => $general_settings->store_name,
            ]);
            

            if (\Schema::hasTable('payment_methods')) {
                $paymentMethods = \App\Models\PaymentMethod::all();

                foreach ($paymentMethods as $paymentMethod) {
                    if ($paymentMethod->is_enabled) {
                        if ($paymentMethod->identifier == 'mercadopago') {
                            config([
                                'services.mercadopago.public_key' => $paymentMethod->meta['public_key'],
                                'services.mercadopago.access_token' => $paymentMethod->meta['access_token'],
                            ]);
                        } 
                    }
                }
            }
        } catch (\Exception $e) {
            return;
        }

        Model::preventLazyLoading(! app()->isProduction());

        Component::macro('notify', function ($message) {
            $this->dispatchBrowserEvent('notify', $message);
        });

       

        View::share('generalSettings', app(GeneralSetting::class));

        View::share('brandSettings', app(BrandSetting::class));

        View::share('layoutSettings', app(LayoutSetting::class));

        View::share('is_local', request()->getHost() == 'localhost' || request()->getHost() == '127.0.0.1' || \Str::endsWith(request()->getHost(), ['.test', '.example', '.invalid', '.local', '.localhost']));

        View::share('is_staging', \Str::startsWith(request()->getHost(), ['dev.', 'demo.', 'test.', 'testing.', 'stage.', 'staging.', 'development.']));
    }
}
