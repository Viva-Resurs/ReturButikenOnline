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

        $result = [];
        foreach (Image::all() as $image){
            array_push($result,[
                'id' => $image->id,
                'name' => $image->name,
                'original_name' => $image->original_name,
                'path' => $image->path,
                'thumb_path' => $image->thumb_path,
                'created_at' => $image->created_at->format('Y-m-d h:m:s'),
                'updated_at' => $image->updated_at->format('Y-m-d h:m:s')
            ]);
        }

        return $result;
    }

    public function store(Request $request){

        $user = Auth::user();

        if (!$user)
            abort(401,'Not allowed to create images');

        foreach ($request->file('files') as $file) {

            $file_path = $file->store('uploads','public');

            $name = substr( $file_path, 8, strripos( $file_path, '.' )-8 );

            $ext = substr( $file_path, strripos( $file_path, '.' )+1 );

            $thumb_path = 'uploads/_'.$name.'.png';

            $tmb = ImageGenerator::canvas(200, 200, array(255, 255, 255, 0.0) );
            $img  = ImageGenerator::make($file_path)->resize(200, 200, function($constraint)
            {
               $constraint->aspectRatio();
            });
            $tmb->insert($img, 'center')->save($thumb_path);

            $exists = Image::where('path',$file_path)->count();

            if ($exists>0)
                return Image::where('path',$file_path)->first();

            $image = new Image([
                'name' => $name,
                'original_name' => $file->getClientOriginalName(),
                'path' => $file_path,
                'thumb_path' => $thumb_path
            ]);

            $image->save();

            return $image;
        }

    }

    public function update(Request $request, $id){

    }

    public function destroy($id){

        $user = Auth::user();

        if (!$user)
            abort(401,'Not allowed to remove images');

        $image = Image::find($id);

        dd($image->articles);

        foreach ($image->article as $association);
        //$image->article

        foreach ($article->images as $image)
            array_push($result['selected_images'], [
                'id' => $image->id,
                'thumb_path' => $image->thumb_path
            ]);

        $exists = Image::where('path',$file_path)->count();

        if (!$image)
            abort(404);

        $image->delete();
    }

}
