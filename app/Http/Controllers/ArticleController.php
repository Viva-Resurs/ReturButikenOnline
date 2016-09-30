<?php

namespace App\Http\Controllers;

use App\Article;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests;

class ArticleController extends Controller
{
    public function index(Request $request){
        //return $results = DB::select('select * from users where id = :id', ['id' => 1]);
        return Article::all();
    }

    public function show($id){
        $article = Article::find($id);

        if (!$article)
            abort(404);

        return $article;
    }

    public function store(Request $request){
        
        $article = new Article([
            'name' => $request->name || '',
            'desc' => $request->desc || '',
            'public' => $request->public || 0,
            'publish_interval' => $request->publish_interval || '',
            'bidding_interval' => $request->bidding_interval || '',
        ]);

        $article->save();
    }

    public function update(Request $request, $id){
        
        $article = Article::find($id);

        if (!$article)
            abort(404);

        if ($request->has('name') && $request['name']!='')
            $article->name = $request['name'];

        if ($request->has('desc') && $request['desc']!='')
            $article->desc = $request['desc'];

        if ($request->has('public'))
            $article->public = $request['public'];

        if ($request->has('publish_interval') && $request['publish_interval']!='')
            $article->publish_interval = $request['publish_interval'];

        if ($request->has('bidding_interval') && $request['bidding_interval']!='')
            $article->bidding_interval = $request['bidding_interval'];

        $article->save();
    }

    public function destroy($id){
        
        $article = Article::find($id);

        if (!$article)
            abort(404);

        $article->delete();
    }
}
