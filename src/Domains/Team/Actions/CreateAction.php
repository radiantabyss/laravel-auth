<?php
namespace RA\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Services\ClassName;
use RA\Auth\Services\Jwt;

class CreateAction extends Action
{
    public function run() {
        $data = \Request::all();

        //validate request
        $validation = ClassName::Validator('Team\Validator')::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        //transform data
        $data = ClassName::Transformer('Team\Transformer')::run($data);
        $meta = $data['meta'];
        unset($data['meta']);

        //handle image upload
        $data = $this->handleImageUpload($data);

        //insert
        $item = ClassName::Model('UserTeam')::create($data);

        //insert meta keys
        foreach ( $meta as $key => $value ) {
            ClassName::Model('UserTeamMeta')::create([
                'team_id' => $item->id,
                'key' => $key,
                'value' => $value,
            ]);
        }

        //insert user in own team
        ClassName::Model('UserTeamMember')::create([
            'team_id' => $item->id,
            'user_id' => \Auth::user()->id,
            'role' => 'owner',
        ]);

        $item = ClassName::Presenter('Team\Presenter')::run($item);

        //regenerate jwt token
        $user = ClassName::Presenter('User\Presenter')::run(\Auth::user(), $item->id);
        $jwt_token = Jwt::generate(ClassName::Presenter('User\JwtPresenter')::run(clone $user));

        return Response::success(compact('item', 'user', 'jwt_token'));
    }

    private function handleImageUpload($data) {
        if ( !isset($data['image']) ) {
            return $data;
        }

        $pathinfo = pathinfo($data['image']->getClientOriginalName());
        $image_name = \Str::slug($pathinfo['filename']).'-'.\Str::random(6).'.'.$pathinfo['extension'];
        $path = config('path.uploads_path').'/'.\Auth::user()->id;

        //make folder if not exists
        if ( !file_exists($path) ) {
            mkdir($path, 0777);
        }

        //upload
        $data['image']->move($path, $image_name);
        $image_path = \Auth::user()->id.'/'.$image_name;

        //resize
        if ( extension_loaded('imagick') ) {
            Image::configure(array('driver' => 'imagick'));
        }

        Image::make($path.'/'.$image_path)->fit(300, 300)->save(null, 100);

        unset($data['image']);

        if ( !isset($data['meta']) ) {
            $data['meta'] = [];
        }

        $data['meta']['image_path'] = $image_path;

        return $data;
    }
}
