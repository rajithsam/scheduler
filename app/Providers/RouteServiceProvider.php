<?php

namespace Scheduler\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Route;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Users\Repository\UserRepository;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Scheduler\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     */
    public function boot(Router $router)
    {
        $userRepository = app(UserRepository::class);

        Route::bind('user', function($value) use ($userRepository)
        {
            return $userRepository->getOneByIdOrFail($value);
        });

        $shiftRepository = app(ShiftRepository::class);

        Route::bind('shift', function($value) use ($shiftRepository)
        {
//            dd($value);
            return $shiftRepository->getOneByIdOrFail($value);
        });

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
