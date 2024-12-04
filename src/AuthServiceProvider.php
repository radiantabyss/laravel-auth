<?php
namespace Lumi\Auth;

use Illuminate\Support\ServiceProvider;
use Lumi\Auth\Services\ClassName;

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
        \View::addNamespace('Lumi.Auth.Team', __DIR__.'/Domains/Team/Mail/views');
        \View::addNamespace('Lumi.Auth.User', __DIR__.'/Domains/User/Mail/views');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //load configs
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'lumi-auth');

        //register middleware
        $this->registerMiddleware();
    }

    private function registerMiddleware() {
        $router = $this->app['router'];
        $router->aliasMiddleware('Lumi\Auth\Logged', \Lumi\Auth\Http\Middleware\LoggedMiddleware::class);
        $router->aliasMiddleware('Lumi\Auth\NotLogged', \Lumi\Auth\Http\Middleware\NotLoggedMiddleware::class);
        $router->aliasMiddleware('Lumi\Auth\SetUser', \Lumi\Auth\Http\Middleware\SetUserMiddleware::class);
        $router->aliasMiddleware('Lumi\Auth\TeamRole', \Lumi\Auth\Http\Middleware\TeamRoleMiddleware::class);
        $router->aliasMiddleware('Lumi\Auth\UserType', \Lumi\Auth\Http\Middleware\UserTypeMiddleware::class);
    }

    private function enablePublishing() {
        if ( !\App::runningInConsole() ) {
            return;
        }

        //config
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('lumi-auth.php'),
        ], 'lumi-auth:config');

        //routes
        $this->publishes([
            __DIR__.'/../routes/routes.php' => base_path('routes/lumi-auth.php'),
        ], 'lumi-auth:routes');

        //migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => base_path('database/migrations/lumi-auth'),
        ], 'lumi-auth:migrations');

        $domains = ['Team', 'User'];
        $items = ['Actions', 'Commands', 'Mail', 'Presenters', 'Transformers', 'Validators'];
        foreach ( $domains as $domain ) {
            foreach ( $items as $item ) {
                $this->publishes([
                    __DIR__.'/Domains/'.$domain.'/'.$item => app_path('Domains/Auth/'.$domain.'/'.$item),
                ], 'lumi-auth:'.strtolower($domain.'-'.$item));
            }
        }
    }
}
