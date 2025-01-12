<?php
namespace RA\Auth;

use Illuminate\Support\ServiceProvider;
use RA\Auth\Services\ClassName;

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
        \View::addNamespace('RA.Auth.Team', __DIR__.'/Domains/Team/Mail/views');
        \View::addNamespace('RA.Auth.User', __DIR__.'/Domains/User/Mail/views');
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
        $router->aliasMiddleware('RA\Auth\NotLogged', \RA\Auth\Http\Middleware\NotLoggedMiddleware::class);
        $router->aliasMiddleware('RA\Auth\SetUser', \RA\Auth\Http\Middleware\SetUserMiddleware::class);
        $router->aliasMiddleware('RA\Auth\TeamRole', \RA\Auth\Http\Middleware\TeamRoleMiddleware::class);
        $router->aliasMiddleware('RA\Auth\UserType', \RA\Auth\Http\Middleware\UserTypeMiddleware::class);
    }

    private function enablePublishing() {
        if ( !\App::runningInConsole() ) {
            return;
        }

        //config
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('ra-auth.php'),
        ], 'ra-auth:config');

        //routes
        $this->publishes([
            __DIR__.'/../routes/routes.php' => base_path('routes/ra-auth.php'),
        ], 'ra-auth:routes');

        //migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => base_path('database/migrations/ra-auth'),
        ], 'ra-auth:migrations');

        $domains = ['Team', 'User'];
        $items = ['Actions', 'Commands', 'Mail', 'Presenters', 'Transformers', 'Validators'];
        foreach ( $domains as $domain ) {
            foreach ( $items as $item ) {
                $this->publishes([
                    __DIR__.'/Domains/'.$domain.'/'.$item => app_path('Domains/Auth/'.$domain.'/'.$item),
                ], 'ra-auth:'.strtolower($domain.'-'.$item));
            }
        }
    }
}
