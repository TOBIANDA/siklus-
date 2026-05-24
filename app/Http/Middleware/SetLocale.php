<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     * Runs after session middleware, so session() is available.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $locale = Auth::user()->language_preference ?? session('locale', 'en');
        } else {
            $locale = session('locale', 'en');
        }

        // Safety: only allow supported locales
        $supported = ['en', 'id'];
        if (!in_array($locale, $supported)) {
            $locale = 'en';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
