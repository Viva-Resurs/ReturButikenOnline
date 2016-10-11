<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
	public function index(){

		$user = Auth::user();

		if (!$user)
			abort(401,'Not allowed to list users');

        return User::all();
    }

    public function show($id){
        $user = User::find($id);

        if (!$user)
            abort(404);

        return $user;
    }

}
