<?php

namespace App\Providers;

use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use App\Http\Requests\Request;

class RequestServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->app->resolving(Request::class, function (Request $request, Container $app) {
            $request->initialize(
                $app->request->query->all(), $app->request->request->all(), $app->request->attributes->all(),
                $app->request->cookies->all(), $app->request->files->all(), $app->request->server->all()
            );
            $request->setUserResolver($app->request->getUserResolver());
            return $request;
        });
    }
}
