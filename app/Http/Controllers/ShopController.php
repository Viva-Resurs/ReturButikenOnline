<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Image;
use App\User;
use App\Token;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests;

class ShopController extends Controller
{
    public function index(Request $request){

        $token = Token::where('token', $request->header('INTERNAL-TOKEN'))->first();

        $audience = 'public';

        if (!is_null($token))
            $audience = 'internal';

        $result = [];
        foreach (Article::all() as $article){
            if ($article->isActive())
                if ($article->matchAudience($audience))
                    array_push($result,[
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
                        'created_by' => $article->creator,
                        'is_active' => $article->isActive()
                    ]);
        }

        return $result;
    }

    public function show(Request $request, $id){

        $article = Article::find($id);

        if (!$article)
            abort(404);

        $token = Token::where('token', $request->header('INTERNAL-TOKEN'))->first();

        $audience = 'public';

        if (!is_null($token))
            $audience = 'internal';

        if (!$article->matchAudience($audience))
            abort(404);

        $result = [
            'id' => $article->id,
            'name' => $article->name,
            'desc' => $article->desc,
            'price' => $article->price,
            'updated_at' => $article->updated_at,
            'publish_interval' => $article->publish_interval,
            'bidding_interval' => $article->bidding_interval,
            'public' => $article->public,
            'sections' => $article->sections,
            'categories' => $article->categories,
            'contacts' => [],
            'images' => [],
            'is_active' => $article->isActive()
        ];

        foreach ($article->contacts as $contact){
            array_push($result['contacts'],[
                'id' => $contact->id,
                'fullname' => $contact->fullname,
                'phone' => $contact->phone,
                'email' => $contact->email,
                'images' => User::find($contact->id)->images()->orderBy('order')->get()
            ]);
        }

        foreach ($article->images()->orderBy('order')->get() as $image)
            array_push($result['images'], [
                'id' => $image->id,
                'name' => $image->name,
                'original_name' => $image->original_name,
                'path' => $image->path . '?',
                'thumb_path' => $image->thumb_path . '?',
                'order' => $image->order
            ]);

        return $result;
    }
}
