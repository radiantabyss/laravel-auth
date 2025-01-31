<?php
namespace RA\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Response;
use Intervention\Image\ImageManagerStatic as Image;
use RA\Auth\Services\ClassName;

class UploadImageAction extends Action
{
    public function run($team_id) {
        $user_id = \Auth::user()->id;
        $data = \Request::all();

        //validate request data
        $validation = ClassName::Validator('Team\UploadImageValidator')::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        $pathinfo = pathinfo($data['file']->getClientOriginalName());
        $image_name = \Str::slug($pathinfo['filename']).'-'.\Str::random(5).'.'.$pathinfo['extension'];

        //set destination path
        $path = config('path.uploads_path').'/user-team-images/'.$team_id;

        //make folder if not exists
        if ( !file_exists($path) ) {
            mkdir($path, 0777);
        }

        //upload
        $data['file']->move($path, $image_name);

        //resize
        if ( extension_loaded('imagick') ) {
            Image::configure(array('driver' => 'imagick'));
        }

        Image::make($path.'/'.$image_name)->fit(300, 300)->save(null, 100);

        return Response::success([
            'path' => '/user-team-images/'.$team_id.'/'.$image_name,
        ]);
    }
}
