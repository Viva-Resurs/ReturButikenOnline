<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
	public function index(){

		$user = Auth::user();

        if (!$user || !$user->hasRole('admin'))
			abort(401,'Not allowed to list users');

        return User::all();
    }

    public function show($id){

        $user = Auth::user();

        if (!$user || !$user->hasRole('admin'))
            abort(401,'Not allowed to show users');

        $showUser = User::find($id);

        if (!$showUser)
            abort(404, 'Could not find user');

        return $showUser;
    }

    /**
     * Get currently authorized user
     */
	public function me(){
		return Auth::user();
    }

}
