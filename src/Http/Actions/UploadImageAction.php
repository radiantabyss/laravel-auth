<?php
namespace RA\Auth\Http\Actions;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as Action;
use App\Core\Response;
use RA\Auth\Validators\UploadImageValidator as Validator;
use Intervention\Image\ImageManagerStatic as Image;

class UploadImageAction extends Action
{
    public function run(Request $request) {
        $item = \Auth::user();
        $data = $request->all();

        //validate request data
        $validation = Validator::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        //prettify the name
        $pathinfo = pathinfo($request->file->getClientOriginalName());
        $image_name = \Str::slug($pathinfo['filename']).'-'.\Str::random(5).'.'.$pathinfo['extension'];

        //get destination path
        $path = config('ra-auth.uploads_path');

        //make  folder if not exists
        if ( !file_exists($path.'/'.$item->id) ) {
            mkdir($path.'/'.$item->id, 0777);
        }

        //upload
        $request->file->move($path.'/'.$item->id, $image_name);

        //resize
        $image_url = basename($path).'/'.$item->id.'/'.$image_name;

        if ( extension_loaded('imagick') ) {
            Image::configure(array('driver' => 'imagick'));
        }

        Image::make($path.'/'.$item->id.'/'.$image_name)->fit(300, 300)->save(null, 100);

        return Response::success(compact('image_url'));
    }
}
