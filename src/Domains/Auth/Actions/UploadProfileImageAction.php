<?php
namespace RA\Auth\Domains\Auth\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use Intervention\Image\ImageManagerStatic as Image;
use RA\Auth\Services\ClassName;

class UploadProfileImageAction extends Action
{
    public function run() {
        $item = \Auth::user();
        $data = \Request::all();

        //validate request data
        $validation = ClassName::Validator('UploadProfileImageValidator')::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        //prettify the name
        $pathinfo = pathinfo($data['file']->getClientOriginalName());
        $image_name = \Str::slug($pathinfo['filename']).'-'.\Str::random(5).'.'.$pathinfo['extension'];

        //set destination path
        $path = config('ra-auth.uploads_path').'/user-profile-images';

        //make folder if not exists
        if ( !file_exists($path.'/'.$item->id) ) {
            mkdir($path.'/'.$item->id, 0777);
        }

        //upload
        $data['file']->move($path.'/'.$item->id, $image_name);

        //resize
        $image_path = basename($path).'/'.$item->id.'/'.$image_name;

        if ( extension_loaded('imagick') ) {
            Image::configure(array('driver' => 'imagick'));
        }

        Image::make($image_path)->fit(300, 300)->save(null, 100);

        return Response::success(compact('image_path'));
    }
}
