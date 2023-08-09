<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class SetAppLocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        //$locale = request('locale',Cookie::get('locale',config('app.locale')));
        //Cookie::queue('locale',$locale, 60 * 24 * 365);

        $locale = $request->route('locale');
        App::setLocale($locale);
        URL::defaults([
            'locale' => $locale,
        ]);

       Route::current()->forgetParameter('locale');

        return $next($request);
    }
}
