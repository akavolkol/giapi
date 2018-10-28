<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(dirname(__DIR__)))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);
$app->configure('app');
$app->configure('github');
$app->configure('mail');
$app->configure('filesystems');
$app->withEloquent();

$app->singleton(
 Illuminate\Contracts\Debug\ExceptionHandler::class,
 App\Exceptions\Handler::class
);
$app->singleton(
 Illuminate\Contracts\Console\Kernel::class,
 Laravel\Lumen\Console\Kernel::class
);
$app->alias('mailer', Illuminate\Mail\Mailer::class);
$app->alias('mailer', Illuminate\Contracts\Mail\Mailer::class);
$app->alias('mailer', Illuminate\Contracts\Mail\MailQueue::class);
$app->make('queue');

$app->routeMiddleware([
     'auth' => App\Http\Middleware\Authenticate::class,
 ]);

$app->register(App\Providers\AuthServiceProvider::class);
$app->register(App\Providers\RequestServiceProvider::class);
$app->register(Illuminate\Mail\MailServiceProvider::class);
$app->register(Illuminate\Filesystem\FilesystemServiceProvider::class);

$app->router->group(
    [
        'namespace' => 'App\Http\Controllers',
        'prefix' => '/api'
    ],
    function ($router) {
        $router->post('/github/send-email', ['middleware' => 'auth', 'uses' => 'API\GitHubUser@sendEmail']);
        $router->post('/users', ['uses' => 'API\User@create']);
        $router->post('/auth', ['uses' => 'API\User@auth']);
    }
);
$app->router->get('/docs', ['uses' => 'App\Http\Controllers\Docs@api']);
return $app;
