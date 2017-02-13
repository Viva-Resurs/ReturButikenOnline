<?php

namespace App\Http\Controllers;

use App\Section;
use App\User;
use App\Article;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests;

class overviewController extends Controller
{
    public function getArticleTree(Request $request){

        $me = Auth::user();

        if (!$me)
            abort(401,'Not logged in');

        // Build the tree
        $articleTree = [];
        foreach (Section::all() as $section){
            $tSection = [
                'id' => $section['id'],
                'name' => $section['name'],
                'contacts' => []
            ];
            foreach ($section->users as $user){
                if ($user->hasRole('sectionadmin')){
                    $tUser = [
                        'id' => $user->id,
                        'name' => $user->name,
                        'articles' => []
                    ];
                    foreach (Article::all() as $article){
                        foreach ($article->contacts as $article_contact){
                            if ($article_contact->id == $user->id){
                                array_push($tUser['articles'], $article);
                            }
                        }
                    }
                    array_push($tSection['contacts'], $tUser);
                }
            }
            if ($me->hasRole('admin'))
                array_push($articleTree, $tSection);
            else
                foreach ($me->sections as $user_section) {
                    if ($user_section->id == $section->id)
                        array_push($articleTree, $tSection);
                }
        }

        return $articleTree;
    }

    public function getMyArticles(Request $request){

        $me = Auth::user();

        if (!$me)
            abort(401,'Not logged in');

        $articles = [];
        foreach (Article::all() as $article)
            foreach ($article->creator as $creator)
                if ($creator->id == $me->id)
                    array_push($articles, $article);
        return $articles;
    }
}
