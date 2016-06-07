<?php

namespace Josevh\JiraCal;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class JiraCalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadViewsFrom(__DIR__.'/views', 'jiracal');

        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/josevh/jiracal'),
        ], 'views');

        $this->publishes([
            __DIR__.'/config/jiracal.php' => config_path('jiracal.php'),
        ], 'config');

        parent::boot($router);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/routes.php';
        $this->app->make('Josevh\JiraCal\JiraCalController');
        $this->mergeConfigFrom(
            __DIR__.'/config/jiracal.php', 'jiracal'
        );
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $this->mapWebRoutes($router);

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapWebRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace, 'middleware' => 'web',
        ], function ($router) {
            require __DIR__ . '/routes.php';
        });
    }

}
