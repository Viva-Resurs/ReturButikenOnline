<?php

namespace App\Http\Controllers;

use App\Category;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests;

class CategoryController extends Controller
{
    /**
     * Get a list of categories.
     *
     * @param  Request $request
     * @return array $result
     */
    public function index(Request $request){

		$user = Auth::user();

        //if (!$user)
		//	abort(401,'Not allowed to list categories');

        return Category::all();
    }


    /**
     * Creates a new category.
     *
     * @param  request $request
     * @return $category
     */
    public function store(Request $request){

		$user = Auth::user();

        if (!$user || !$user->hasRole('admin'))
			abort(401,'Not allowed to create categories');

        $category = new Category([
            'name' => ($request->has('name')) ? $request['name'] : 'namnlös kategori'
        ]);

        $category->save();

        return $category;
    }

    /**
     * Updates a existing category using category id.
     *
     * @param  request $request
     * @param $id
     * @return $category
     */
    public function update(Request $request, $id){

		$user = Auth::user();

        if (!$user || !$user->hasRole('admin'))
			abort(401,'Not allowed to update categories');

        $category = Category::find($id);

        if (!$category)
            abort(404);

        if ($request->has('name') && $request['name']!='')
            $category->name = $request['name'];

        $category->save();

        return $category;
    }
    
    /**
     * Removes a category using category id.
     * 
     * @param  $id     
     */
    public function destroy($id){

		$user = Auth::user();

        if (!$user || !$user->hasRole('admin'))
			abort(401,'Not allowed to remove categories');

        $category = Category::find($id);

        if (!$category)
            abort(404);

        $category->delete();
    }

}
