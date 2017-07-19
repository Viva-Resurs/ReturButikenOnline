<?php

namespace App\Http\Controllers;

use App\Token;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests;

class TokenController extends Controller
{
    /**
     * Get a list of tokens.
     *
     * @param  Request $request
     * @return array $result
     */
    public function index(Request $request){

		$user = Auth::user();
        if (!$user || !$user->hasRole('admin'))
            abort(401,'Not allowed to list tokens');

        return Token::all();
    }

    /**
     * Creates a new randomly generated token. 
     *
     * @param  request $request
     * @return $token
     */
    public function store(Request $request){

		$user = Auth::user();

        if (!$user || !$user->hasRole('admin'))
			abort(401,'Not allowed to create tokens');

        $token = new Token();
        $token->generate();

        $token->save();

        return $token;
    }

    /**
     * Removes a token using token id. 
     *
     * @param $id     
     */
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
