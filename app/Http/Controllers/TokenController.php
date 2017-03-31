<?php

namespace App\Http\Controllers;

use App\Token;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests;

class TokenController extends Controller
{
    public function index(Request $request){

		$user = Auth::user();
        if (!$user || !$user->hasRole('admin'))
            abort(401,'Not allowed to list tokens');
        
        return Token::all();
    }

    public function store(Request $request){

		$user = Auth::user();

        if (!$user || !$user->hasRole('admin'))
			abort(401,'Not allowed to create tokens');


        $token = new Token([
            'token' => $this->generateToken()
        ]);

        $token->save();

        return $token;
    }

    
    private function generateToken(){
        //TODO: Needs to generate and return a real token
        //$app_key = env(APP_KEY, false);
        return "12345";
    }

    public function destroy($id){

		$user = Auth::user();

        if (!$user || !$user->hasRole('admin'))
			abort(401,'Not allowed to remove token');

        $token = Token::find($id);

        if (!$token)
            abort(404);

        $token->delete();
    }

}
