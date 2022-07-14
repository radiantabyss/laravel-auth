<?php
namespace RA\Auth;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ra-auth');

        //enable publishing
        $this->enablePublishing();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //load configs
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'ra-auth');

        //register middleware
        $this->registerMiddleware();
    }

    private function registerMiddleware() {
        $router = $this->app['router'];
        $router->aliasMiddleware('RA\Auth\Auth', \RA\Auth\Http\Middleware\AuthMiddleware::class);
        $router->aliasMiddleware('RA\Auth\NoAuth', \RA\Auth\Http\Middleware\NoAuthMiddleware::class);
        $router->aliasMiddleware('RA\Auth\SetUser', \RA\Auth\Http\Middleware\SetUserMiddleware::class);
    }

    private function enablePublishing() {
        if ( !\App::runningInConsole() ) {
            return;
        }

        //config
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('ra-auth.php'),
        ], 'ra-auth:config');

        //actions
        $this->publishes([
            __DIR__.'/Http/Actions' => app_path('Http/Actions/RA/Auth'),
        ], 'ra-auth:actions');

        //routes
        $this->publishes([
            __DIR__.'/../routes/routes.php' => base_path('routes/ra-auth.php'),
        ], 'ra-auth:routes');

        //migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => base_path('database/migrations'),
        ], 'ra-auth:migrations');

        //views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/ra/auth'),
        ], 'ra-auth:views');
    }
}
