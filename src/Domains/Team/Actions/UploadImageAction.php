<?php
namespace Lumi\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use Lumi\Core\Response;
use Intervention\Image\ImageManagerStatic as Image;
use Lumi\Auth\Services\ClassName;

class UploadImageAction extends Action
{
    public function run() {
        $user_id = \Auth::user()->id;
        $data = \Request::all();

        //validate request data
        $validation = ClassName::Validator('Team\UploadImageValidator')::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        //prettify the name
        $pathinfo = pathinfo($data['file']->getClientOriginalName());
        $image_name = \Str::slug($pathinfo['filename']).'-'.\Str::random(5).'.'.$pathinfo['extension'];

        //set destination path
        $path = config('path.uploads_path').'/user-team-images';

        //make folder if not exists
        if ( !file_exists($path.'/'.$user_id) ) {
            mkdir($path.'/'.$user_id, 0777);
        }

        //upload
        $data['file']->move($path.'/'.$user_id, $image_name);

        //resize
        if ( extension_loaded('imagick') ) {
            Image::configure(array('driver' => 'imagick'));
        }

        Image::make($path.'/'.$user_id.'/'.$image_name)->fit(300, 300)->save(null, 100);

        return Response::success([
            'path' => '/user-team-images/'.$user_id.'/'.$image_name,
        ]);
    }
}
