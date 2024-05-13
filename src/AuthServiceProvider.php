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

        //register gates
        $this->registerGates();
    }

    private function registerMiddleware() {
        $router = $this->app['router'];
        $router->aliasMiddleware('Lumi\Auth\Logged', \Lumi\Auth\Http\Middleware\LoggedMiddleware::class);
        $router->aliasMiddleware('Lumi\Auth\NotLogged', \Lumi\Auth\Http\Middleware\NotLoggedMiddleware::class);
        $router->aliasMiddleware('Lumi\Auth\SetUser', \Lumi\Auth\Http\Middleware\SetUserMiddleware::class);
        $router->aliasMiddleware('Lumi\Auth\TeamRole', \Lumi\Auth\Http\Middleware\TeamRoleMiddleware::class);
        $router->aliasMiddleware('Lumi\Auth\UserType', \Lumi\Auth\Http\Middleware\UserTypeMiddleware::class);
    }

    private function registerGates() {
        \Gate::define('owns-team', function($user, $team_id = null) {
            if ( $user->type == 'super_admin' ) {
                return true;
            }

            return ClassName::Model('TeamMember')::where('team_id', $team_id ?: $user->team->id)
                ->where('user_id', $user->id)
                ->where('role', 'owner')
                ->exists();
        });

        \Gate::define('manage-team', function($user, $team_id = null) {
            if ( $user->type == 'super_admin' ) {
                return true;
            }

            return ClassName::Model('TeamMember')::where('team_id', $team_id ?: $user->team->id)
                ->where('user_id', $user->id)
                ->whereIn('role', ['owner', 'admin'])
                ->exists();
        });
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
