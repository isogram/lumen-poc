<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

// enabling facades
$app->withFacades();

// enabling configs
$app->configure('app');
$app->configure('api');
$app->configure('jwt');
$app->configure('auth');
$app->configure('cache');
$app->configure('mail');

// Uncomment these line if you want to enable JWT
// class_alias(Tymon\JWTAuth\Facades\JWTAuth::class, 'JWTAuth');
// class_alias(Tymon\JWTAuth\Facades\JWTFactory::class, 'JWTFactory');

// enabling eloquent
$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Routing\ResponseFactory::class,
    Illuminate\Routing\ResponseFactory::class
);

$app->singleton(
    Illuminate\Auth\AuthManager::class,
    function ($app) {
        return $app->make('auth');
    }
);

$app->singleton(
    Illuminate\Cache\CacheManager::class,
    function ($app) {
        return $app->make('cache');
    }
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//    App\Http\Middleware\ExampleMiddleware::class
// ]);

$app->routeMiddleware([
    'auth'        => App\Http\Middleware\Authenticate::class,
    
    // Uncomment these line if you want to enable JWT
    // 'jwt.auth'    => App\Http\Middleware\GetUserFromToken::class,
    // 'jwt.refresh' => App\Http\Middleware\RefreshToken::class,
    
    // Uncomment this line if you want to enable RateLimiter
    // 'ut.throttle' => App\Http\Middleware\RateLimit::class,
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

// $app->register(App\Providers\AppServiceProvider::class);
// $app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

// Guard
$app->register(App\Providers\GuardServiceProvider::class);

// Redis 
$app->register(Illuminate\Redis\RedisServiceProvider::class);

// Mailer
$app->register(Illuminate\Mail\MailServiceProvider::class);

// Uncomment this line if you want to enable JWT
// JWTAuth
// $app->register(Tymon\JWTAuth\Providers\JWTAuthServiceProvider::class);

// Dingo API
$app->register(Dingo\Api\Provider\LumenServiceProvider::class);

// Uncomment these line if you want to enable JWT
// Dingo API: Extending JWT Auth
// $app->make(Dingo\Api\Auth\Auth::class)->extend('jwt', function ($app) {
//     return new Dingo\Api\Auth\Provider\JWT($app->make(Tymon\JWTAuth\JWTAuth::class));
// });

// Dingo API: Override default serializer fractal
$app->make(Dingo\Api\Transformer\Factory::class)->setAdapter(function ($app) {
    $fractal = new League\Fractal\Manager;
    $fractal->setSerializer(new App\Serializers\DataSerializer);
    return new Dingo\Api\Transformer\Adapter\Fractal($fractal);
});

// Uncomment these line if you want to enable RateLimiter
// Dingo API: Override default Rate Limiting Key
// $app->make(Dingo\Api\Http\RateLimit\Handler::class)->setRateLimiter(function ($app, $request) {
//     $token = $request->bearerToken();
//     return $token ?: $request->getClientIp();
// });


/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->group(['namespace' => App\Api\v1\Controllers::class], function ($app) {
    require __DIR__.'/../app/Api/routes.php';
});

return $app;
