<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Settings\PreferencesSettings;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        try {
            /** @var string $locale */
            $locale = resolve(PreferencesSettings::class)->locale;
        } catch (Exception) {
            /** @var string $locale */
            $locale = config('app.locale');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
