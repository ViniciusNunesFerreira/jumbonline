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
use App\Observers\OrderObserver;
use App\Observers\PaymentObserver;
use App\Observers\RefundObserver;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Refund;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

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

        \Carbon\Carbon::setLocale('pt_BR');

        Password::defaults(fn () => Password::min(8)->mixedCase()->numbers());

        Model::preventLazyLoading(! app()->isProduction());

        Component::macro('notify', function ($message) {
            $this->dispatchBrowserEvent('notify', $message);
        });

        // CRM (Módulo 1): mantém ltv_total / paid_orders_count / last_order_at
        // do Customer atualizados a cada evento de pedido, pagamento ou reembolso.
        Order::observe(OrderObserver::class);
        Payment::observe(PaymentObserver::class);
        Refund::observe(RefundObserver::class);

        View::share('generalSettings', app(GeneralSetting::class));

        View::share('brandSettings', app(BrandSetting::class));

        View::share('layoutSettings', app(LayoutSetting::class));

        View::share('is_local', request()->getHost() == 'localhost' || request()->getHost() == '127.0.0.1' || \Str::endsWith(request()->getHost(), ['.test', '.example', '.invalid', '.local', '.localhost']));

        View::share('is_staging', \Str::startsWith(request()->getHost(), ['dev.', 'demo.', 'test.', 'testing.', 'stage.', 'staging.', 'development.']));
    }
}