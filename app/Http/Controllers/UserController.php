<?php

namespace App\Http\Controllers;

use App\User;

class UserController extends Controller
{
	public function index(){
        return User::all();
    }

    public function show($id){
        $user = User::find($id);

        if (!$user)
            abort(404);

        return $user;
    }

}
