<?php
namespace Lumi\Auth\Http\Middleware;

use Lumi\Core\Response;

class ManageTeamMiddleware
{
    public function handle($request, \Closure $next)
    {
        $team_id = $request->route('team_id') ?? $request->get('team_id');

        if ( \Gate::denies('manage-team', $team_id) ) {
            return Response::error('Not enough privileges.');
        }

        return $next($request);
    }
}
