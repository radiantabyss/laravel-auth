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
        //enable publishing
        $this->enablePublishing();

        //register view domains
        $domains = [
            'User', 'Team'
        ];
        foreach ( $domains as $domain ) {
            \View::addNamespace('RA.Auth.'.$domain, __DIR__.'/Domains/'.$domain.'/Mail/views');
        }
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
        $router->aliasMiddleware('RA\Auth\Logged', \RA\Auth\Http\Middleware\LoggedMiddleware::class);
        $router->aliasMiddleware('RA\Auth\NotLogged', \RA\Auth\Http\Middleware\NoLoggedMiddleware::class);
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
            __DIR__.'/Domains/User' => app_path('Domains/RA/Auth/User'),
        ], 'ra-auth:auth');

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
