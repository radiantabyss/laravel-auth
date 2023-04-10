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
        \View::addNamespace('RA.Auth', __DIR__.'/Domains/Auth/Mail/views');
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
        $router->aliasMiddleware('RA\Auth\Role', \RA\Auth\Http\Middleware\RoleMiddleware::class);
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
            __DIR__.'/Domains/Auth/Actions' => app_path('Domains/Auth/Actions'),
        ], 'ra-auth:actions');

        //commands
        $this->publishes([
            __DIR__.'/Domains/Auth/Commands' => app_path('Domains/Auth/Commands'),
        ], 'ra-auth:commands');

        //mail
        $this->publishes([
            __DIR__.'/Domains/Auth/Mail' => app_path('Domains/Auth/Mail'),
        ], 'ra-auth:mail');

        //presenters
        $this->publishes([
            __DIR__.'/Domains/Auth/Presenters' => app_path('Domains/Auth/Presenters'),
        ], 'ra-auth:presenters');

        //transformers
        $this->publishes([
            __DIR__.'/Domains/Auth/Transformers' => app_path('Domains/Auth/Transformers'),
        ], 'ra-auth:transformers');

        //validators
        $this->publishes([
            __DIR__.'/Domains/Auth/Validators' => app_path('Domains/Auth/Validators'),
        ], 'ra-auth:validators');

        //routes
        $this->publishes([
            __DIR__.'/../routes/routes.php' => base_path('routes/ra-auth.php'),
        ], 'ra-auth:routes');

        //migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => base_path('database/migrations'),
        ], 'ra-auth:migrations');
    }
}
