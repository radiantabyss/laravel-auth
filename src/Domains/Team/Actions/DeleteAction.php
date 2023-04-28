<?php
namespace RA\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Services\ClassName;

class DeleteAction extends Action
{
    public function run($id) {
        $item = ClassName::Model('UserTeam')::find($id);

        if ( !$item ) {
            return Response::error('Team not found.');
        }

        if ( \Gate::denies('manage-team', $id) ) {
            return Response::error('Sorry, you can\'t delete this team.');
        }

        //delete meta
        ClassName::Model('UserTeamMeta')::where('team_id', $id)->delete();

        //delete members
        ClassName::Model('UserTeamMember')::where('team_id', $id)->delete();

        $item->delete();

        //switch team if is current team
        $response = $this->handleCurrentTeam($id);

        return Response::success($response);
    }

    private function handleCurrentTeam($id) {
        if ( \Auth::user()->team->id != $id ) {
            return null;
        }

        $team_member = ClassName::Model('UserTeamMember')::where('user_id', \Auth::user()->id)->first();

        if ( $team_member ) {
            $team_id = $team_member->team_id;
        }
        //user is not part of any teams, create a default one
        else {
            //create default team
            $team = ClassName::Model('UserTeam')::create([
                'user_id' => \Auth::user()->id,
                'name' => 'My Team',
            ]);

            //insert user in own team
            ClassName::Model('UserTeamMember')::create([
                'team_id' => $team->id,
                'user_id' => \Auth::user()->id,
                'role' => 'owner',
            ]);

            $team_id = $team->id;
        }

        //regenerate jwt token
        $user = ClassName::Presenter('User\Presenter')::run(\Auth::user(), $team_id);
        $jwt_token = Jwt::generate(ClassName::Presenter('User\JwtPresenter')::run(clone $user));

        return compact('user', 'jwt_token');
    }
}
