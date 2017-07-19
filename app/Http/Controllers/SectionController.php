<?php

namespace App\Http\Controllers;

use App\Section;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests;

class SectionController extends Controller
{
    /**
     * Get a list of sections.
     *
     * @param  Request $request
     * @return array $result
     */
    public function index(Request $request){

        $me = Auth::user();

        if (!$me)
            abort(401,'Not logged in');

        $results = [];

        if ($me->hasRole('admin')){
            foreach (Section::all() as $section) {
                array_push( $results, [
                    'id' => $section->id,
                    'name' => $section->name,
                    'name_sv' => $section->name_sv,
                    'users' => $section->users
                ]);
            }
        }
        else {
            foreach (Section::all() as $section) {
                foreach ($me->sections as $user_section) {
                    if ($user_section->id == $section->id)
                        array_push( $results, [
                            'id' => $section->id,
                            'name' => $section->name,
                            'name_sv' => $section->name_sv,
                            'users' => $section->users
                        ]);
                }
            }
        }

        return $results;
    }

    /**
     * Creates a new section.
     *
     * @param  request $request
     * @return $section
     */
    public function store(Request $request){

        $me = Auth::user();

        if (!$me)
            abort(401,'Not logged in');

        if (!$me->hasRole('admin'))
            abort(401,'Not allowed to create sections');

        $section = new Section([
            'name' => ($request->has('name')) ? $request['name'] : 'nameless section',
            'name_sv' => ($request->has('name_sv')) ? $request['name_sv'] : 'namnlös sektion',
            'desc' => ($request->has('desc')) ? $request['desc'] : ''
        ]);

        $section->save();

        return $section;
    }

    /**
     * Updates a existing section using section id.
     *
     * @param  request $request
     * @param $id
     * @return articleid
     */
    public function update(Request $request, $id){

        $me = Auth::user();

        if (!$me)
            abort(401,'Not logged in');

        if (!$me->hasRole('admin'))
            abort(401,'Not allowed to update sections');

        $section = Section::find($id);

        if (!$section)
            abort(404);

        if ($request->has('name') && $request['name']!='')
            $section->name = $request['name'];

        $section->save();

        return $section;
    }

    /**
     * Removes a section using section id. 
     *
     * @param  $id     
     */
    public function destroy($id){

        $me = Auth::user();

        if (!$me)
            abort(401,'Not logged in');

        if (!$me->hasRole('admin'))
            abort(401,'Not allowed to remove sections');

        $section = Section::find($id);

        if (!$section)
            abort(404);

        $section->delete();
    }
}
