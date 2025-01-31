<?php
namespace RA\Auth\Domains\User\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Response;
use Intervention\Image\ImageManagerStatic as Image;
use RA\Auth\Services\ClassName;

class UploadProfileImageAction extends Action
{
    public function run() {
        $item = \Auth::user();
        $data = \Request::all();

        //validate request data
        $validation = ClassName::Validator('User\UploadProfileImageValidator')::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        //prettify the name
        $pathinfo = pathinfo($data['file']->getClientOriginalName());
        $image_name = \Str::slug($pathinfo['filename']).'-'.\Str::random(6).'.'.$pathinfo['extension'];

        //set destination path
        $path = config('path.uploads_path').'/user-profile-images/'.$item->id;

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
            'path' => '/user-profile-images/'.$item->id.'/'.$image_name,
        ]);
    }
}
