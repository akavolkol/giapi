<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use App\Auth\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->viaRequest('api', function (Request $request) {
            if ($request->headers->get('Authorization')) {
                $token = substr(
                    $request->headers->get('Authorization'),
                    strpos($request->headers->get('Authorization'), ' ') + 1
                );
                return (new Auth($this->app))->resolve($token);
            }
            return null;
        });
    }
}
