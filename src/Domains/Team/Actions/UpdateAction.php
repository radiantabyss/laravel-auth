<?php
namespace RA\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Services\ClassName;

class UpdateAction extends Action
{
    public function run($team_id) {
        $data = \Request::all();

        //validate request
        $validation = ClassName::Validator('Team\Validator')::run($data, $team_id);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        //transform data
        $data = ClassName::Transformer('Team\Transformer')::run($data, $team_id);
        $meta = $data['meta'];
        unset($data['meta']);

        //handle image upload
        $data = $this->handleImageUpload($data, $team_id);

        //update
        ClassName::Model('Team')::where('id', $team_id)->update($data);

        //update meta keys
        foreach ( $meta as $key => $value ) {
            ClassName::Model('TeamMeta')::updateOrCreate([
                'team_id' => $team_id,
                'key' => $key,
            ], [
                'team_id' => $team_id,
                'key' => $key,
                'value' => $value,
            ]);
        }

        //format for return
        $item = ClassName::Model('Team')::find($team_id);
        $item = ClassName::Presenter('Team\Presenter')::run($item);

        return Response::success(compact('item'));
    }

    private function handleImageUpload($data, $team_id) {
        if ( !isset($data['image']) ) {
            return $data;
        }

        $pathinfo = pathinfo($data['image']->getClientOriginalName());
        $image_name = \Str::slug($pathinfo['filename']).'-'.\Str::random(6).'.'.$pathinfo['extension'];

        //set destination path
        $path = config('path.uploads_path').'/user-team-images/'.$team_id;

        //make folder if not exists
        if ( !file_exists($path) ) {
            mkdir($path, 0777);
        }

        //upload
        $data['image']->move($path, $image_name);

        //resize
        if ( extension_loaded('imagick') ) {
            Image::configure(array('driver' => 'imagick'));
        }

        Image::make($path.'/'.$image_name)->fit(300, 300)->save(null, 100);

        unset($data['image']);

        if ( !isset($data['meta']) ) {
            $data['meta'] = [];
        }

        $data['meta']['image_path'] = '/user-team-images/'.$team_id.'/'.$image_name;

        return $data;
    }
}
