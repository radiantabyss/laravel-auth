<?php
namespace Lumi\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use Lumi\Core\Response;
use Lumi\Auth\Services\ClassName;

class UpdateAction extends Action
{
    public function run($id) {
        $data = \Request::all();

        //validate request
        $validation = ClassName::Validator('Team\Validator')::run($data, $id);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        //transform data
        $data = ClassName::Transformer('Team\Transformer')::run($data, $id);
        $meta = $data['meta'];
        unset($data['meta']);

        //handle image upload
        $data = $this->handleImageUpload($data);

        //update
        ClassName::Model('Team')::where('id', $id)->update($data);

        //update meta keys
        foreach ( $meta as $key => $value ) {
            ClassName::Model('TeamMeta')::updateOrCreate([
                'team_id' => $id,
                'key' => $key,
            ], [
                'team_id' => $id,
                'key' => $key,
                'value' => $value,
            ]);
        }

        //format for return
        $item = ClassName::Model('Team')::find($id);
        $item = ClassName::Presenter('Team\Presenter')::run($item);

        return Response::success(compact('item'));
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
