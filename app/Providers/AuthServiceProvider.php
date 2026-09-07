<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function ($user, string $token) {
            if ($user instanceof \App\Models\Employee) {
                return route('employee.reset-password', ['token' => $token, 'email' => $user->getEmailForPasswordReset()]);
            }
            return route('password.reset', ['token' => $token, 'email' => $user->getEmailForPasswordReset()]);
        });

        // Módulo 2 (Financeiro): restringe dados de margem/lucro/receita
        // detalhada a funcionários marcados como admin.
        Gate::define('admin', function ($employee) {
            return (bool) $employee->is_admin;
        });
    }
}