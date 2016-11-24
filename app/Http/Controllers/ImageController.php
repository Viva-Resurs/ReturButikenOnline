<?php

namespace App\Http\Controllers;

use App\Image;

use ImageGenerator;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests;

class ImageController extends Controller
{
    public function index(Request $request){

        $user = Auth::user();

        if (!$user || !$user->hasRole('admin'))
            abort(401,'Not allowed to list images');

        return Image::all();
    }

    public function store(Request $request){

        $user = Auth::user();

        if (!$user || !$user->hasRole('admin'))
            abort(401,'Not allowed to create images');

        foreach ($request->file('files') as $file) {

            $file_path = $file->store('uploads','public');

            $tmb = ImageGenerator::make($file_path)->fit(200);
            $thumb_path = 'uploads/_'.$tmb->basename;

            $tmb->save($thumb_path);

            dd('File saved to: '.$file_path.'     Thumbnail saved to: '.$thumb_path);

        }

        dd('nofiles');

        $image = new Image([
            'name' => ($file->has('name')) ? $request['name'] : 'namnlös bild',
            'original_name' => $file->originalName,
            'path' => ($request->has('path')) ? $request['path'] : 'ingen sökväg',
            'thumb_path' => ($request->has('thumb_path')) ? $request['thumb_path'] : 'ingen miniatyrbild'

        ]);

        return $image;
    }

    public function update(Request $request, $id){

        $user = Auth::user();

        if (!$user || !$user->hasRole('admin'))
            abort(401,'Not allowed to update images');

        $image = Image::find($id);

        if (!$image)
            abort(404);

        if ($request->has('name') && $request['name']!='')
            $image->name = $request['name'];

        if ($request->has('original_name') && $request['original_name']!='')
            $image->original_name = $request['original_name'];

        if ($request->has('path') && $request['path']!='')
            $image->path = $request['path'];

        if ($request->has('thumb_path') && $request['thumb_path']!='')
            $image->thumb_path = $request['thumb_path'];

        $image->save();

        return $image;
    }

    public function destroy($id){

        $user = Auth::user();

        if (!$user || !$user->hasRole('admin'))
            abort(401,'Not allowed to remove images');

        $image = Image::find($id);

        if (!$image)
            abort(404);

        $image->delete();
    }

}
