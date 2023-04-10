<?php
namespace RA\Auth\Domains\Auth\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Services\ClassName;

class CreateTeamAction extends Action
{
    public function run(Request $request) {
        $data = \Request::all();

        //validate request
        $validation = ClassName::Validator('TeamValidator')::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        //transform data
        $data = ClassName::Transformer('TeamTransformer')::run($data);
        $meta = $data['meta'];
        unset($data['meta']);

        //handle image upload
        $data = $this->handleImageUpload($data);

        //insert
        $team = ClassName::Model('UserTeam')::create($data);

        //insert meta keys
        foreach ( $meta as $key => $value ) {
            ClassName::Model('UserTeamMeta')::create([
                'team_id' => $team->id,
                'key' => $key,
                'value' => $value,
            ]);
        }

        $team = ClassName::Presenter('TeamPresenter')::run($team);

        //regenerate jwt token
        $item = ClassName::Presenter('Presenter')::run(\Auth::user(), $team_id);
        $jwt_token = Jwt::generate(ClassName::Presenter('JwtPresenter')::run(clone $item));

        return Response::success(compact('item', 'jwt_token', 'team'));
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
