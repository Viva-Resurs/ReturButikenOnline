<?php

namespace App\Http\Controllers;

use App\Article;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests;

class ArticleController extends Controller
{
    public function index(Request $request){

        $user = Auth::user();

        if (!$user)
            abort(401,'Not allowed to list articles');

        return Article::all();
    }

    public function show($id){

        $user = Auth::user();

        if (!$user)
            abort(401,'Not allowed to show articles');

        $article = Article::find($id);

        if (!$article)
            abort(404);

        return $article;
    }

    public function store(Request $request){

        $user = Auth::user();

        if (!$user)
            abort(401,'Not allowed to create articles');

        $article = new Article([
            'name' => ($request->has('name')) ? $request['name'] : '',
            'desc' => ($request->has('desc')) ? $request['desc'] : '',
            'public' => $request['public'] || false,
            'publish_interval' => ($request->has('publish_interval')) ? $request['publish_interval'] : '',
            'bidding_interval' => ($request->has('bidding_interval')) ? $request['bidding_interval'] : '',
        ]);

        $article->save();

        return $article;
    }

    public function update(Request $request, $id){

        $user = Auth::user();

        if (!$user)
            abort(401,'Not allowed to update articles');

        $article = Article::find($id);

        if (!$article)
            abort(404);

        if ($request->has('name') && $request['name']!='')
            $article->name = $request['name'];

        if ($request->has('desc') && $request['desc']!='')
            $article->desc = $request['desc'];

        $article->public = $request['public'] || 0;

        $article->publish_interval = $request['publish_interval'];

        $article->bidding_interval = $request['bidding_interval'];

        $article->save();
    }

    public function destroy($id){

        $user = Auth::user();

        if (!$user)
            abort(401,'Not allowed to remove articles');

        $article = Article::find($id);

        if (!$article)
            abort(404);

        $article->delete();
    }
}
