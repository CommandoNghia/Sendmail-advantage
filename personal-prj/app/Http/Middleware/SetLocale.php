<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $lang = $request->header('Accept-Language');
        $acceptLang = explode(',', config('app.accept-lang'));

        if ($lang && in_array($lang, $acceptLang)) {
            app()->setLocale($lang);
        }
        return $next($request);
    }
}
