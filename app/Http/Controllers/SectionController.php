<?php

namespace App\Http\Controllers;

use App\Section;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests;

class SectionController extends Controller
{
    public function index(Request $request){

		$user = Auth::user();

        if (!$user || !$user->hasRole('admin'))
			abort(401,'Not allowed to list sections');

        return Section::all();
    }

    public function store(Request $request){

		$user = Auth::user();

        if (!$user || !$user->hasRole('admin'))
			abort(401,'Not allowed to create sections');

        $section = new Section([
            'name' => ($request->has('name')) ? $request['name'] : 'namnlös sektion',
            'desc' => ($request->has('desc')) ? $request['desc'] : ''
        ]);

        $section->save();

        return $section;
    }

    public function update(Request $request, $id){

		$user = Auth::user();

        if (!$user || !$user->hasRole('admin'))
			abort(401,'Not allowed to update section');

        $section = Section::find($id);

        if (!$section)
            abort(404);

        if ($request->has('name') && $request['name']!='')
            $section->name = $request['name'];

        $section->save();

        return $section;
    }

    public function destroy($id){

		$user = Auth::user();

        if (!$user || !$user->hasRole('admin'))
			abort(401,'Not allowed to remove sections');

        $section = Section::find($id);

        if (!$section)
            abort(404);

        $section->delete();
    }
}
