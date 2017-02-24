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
                        $tArticles = [
                            'id' => $article->id,
                            'name' => $article->name,
                            'desc' => $article->desc,
                            'price' => $article->price,
                            'updated_at' => $article->updated_at->format('Y-m-d H:i:s'),
                            'publish_interval' => $article->publish_interval,
                            'bidding_interval' => $article->bidding_interval,
                            'public' => $article->public,
                            'sections' => $article->sections,
                            'categories' => $article->categories,
                            'images' => $article->images,
                            'contacts' => $article->contacts,
                            'created_by' => $article->creator
                        ];
                        foreach ($article->contacts as $article_contact){
                            if ($article_contact->id == $user->id){
                                array_push($tUser['articles'], $tArticles);
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
                if ($creator->id == $me->id){
                    $tArticles = [
                        'id' => $article->id,
                        'name' => $article->name,
                        'desc' => $article->desc,
                        'price' => $article->price,
                        'updated_at' => $article->updated_at->format('Y-m-d H:i:s'),
                        'publish_interval' => $article->publish_interval,
                        'bidding_interval' => $article->bidding_interval,
                        'public' => $article->public,
                        'sections' => $article->sections,
                        'categories' => $article->categories,
                        'images' => $article->images,
                        'contacts' => $article->contacts,
                        'created_by' => $article->creator
                    ];
                    array_push($articles, $tArticles);
                }
        return $articles;
    }
}
