<?php
namespace RA\Auth\Domains\User\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Response;
use RA\Auth\Services\ClassName;
use RA\Auth\Events\UserDeleted;
use RA\Auth\Events\TeamDeleted;

class DeleteAction extends Action
{
    public function run() {
        $user = \Auth::user();

        //delete teams owned by the user
        $teams = ClassName::Model('Team')::where('created_by', $user->id)->get();
        foreach ( $teams as $team ) {
            //delete meta
            ClassName::Model('TeamMeta')::where('team_id', $team->id)->delete();

            //delete members
            ClassName::Model('TeamMember')::where('team_id', $team->id)->delete();

            $team->delete();

            //let the app clean up after a deleted team (queue via a ShouldQueue listener if needed)
            event(new TeamDeleted($team));
        }

        //leave any teams owned by others
        ClassName::Model('TeamMember')::where('user_id', $user->id)->delete();

        //delete user meta
        ClassName::Model('UserMeta')::where('user_id', $user->id)->delete();

        //delete user codes
        ClassName::Model('UserCode')::where('user_id', $user->id)->delete();

        //delete user logs
        ClassName::Model('UserLog')::where('user_id', $user->id)->delete();

        $user->delete();

        //let the app clean up after a deleted user (queue via a ShouldQueue listener if needed)
        event(new UserDeleted($user));

        return Response::success();
    }
}
