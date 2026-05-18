<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * @var array<int, string>
     */
    private const SUPPORTED_LOCALES = ['id', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $queryLocale = strtolower((string) $request->query('lang'));

        if (in_array($queryLocale, self::SUPPORTED_LOCALES, true)) {
            $request->session()->put('app_locale', $queryLocale);
        }

        $locale = strtolower((string) $request->session()->get('app_locale', config('app.locale', 'id')));

        if (! in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $locale = 'id';
        }

        app()->setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
