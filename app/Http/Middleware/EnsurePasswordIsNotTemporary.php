<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Bloqueia o acesso à área logada da conta enquanto o cliente estiver com
 * uma senha temporária pendente (ver CustomerPasswordReset, no painel).
 * Guarda a URL que ele tentou acessar para devolvê-lo lá depois da troca.
 */
class EnsurePasswordIsNotTemporary
{
    public function handle(Request $request, Closure $next)
    {
        $customer = Auth::guard('customer')->user();

        if ($customer && $customer->must_change_password && ! $request->routeIs('customer.password.temporary')) {
            session()->put('url.intended', $request->fullUrl());

            return redirect()->route('customer.password.temporary');
        }

        return $next($request);
    }
}