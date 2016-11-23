<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;

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

        $result = [];
        foreach (Article::all() as $article)
            array_push($result,[
                'id' => $article->id,
                'name' => $article->name,
                'desc' => $article->desc,
                'updated_at' => $article->updated_at->format('Y-m-d h:m:s'),
                'publish_interval' => $article->publish_interval,
                'bidding_interval' => $article->bidding_interval,
                'public' => $article->public,
                'selected_categories' => $article->categories,
                'selected_images' => $article->images
            ]);

        return $result;
    }

    public function show($id){

        $user = Auth::user();

        if (!$user)
            abort(401,'Not allowed to show articles');

        $article = Article::find($id);

        if (!$article)
            abort(404);

        $result = [
            'id' => $article->id,
            'name' => $article->name,
            'desc' => $article->desc,
            'updated_at' => $article->updated_at,
            'publish_interval' => $article->publish_interval,
            'bidding_interval' => $article->bidding_interval,
            'public' => $article->public,
            'selected_categories' => [],
            'selected_images' => []
        ];

        foreach ($article->categories as $category)
            array_push($result['selected_categories'],$category->id);

        foreach ($article->images as $image)
            array_push($result['selected_images'], $image->id);

        return $result;
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

        // Attach Categories
        if ($request['selected_categories'])
            foreach ($request['selected_categories'] as $category){
                $c = Category::find($category);
                $article->categories()->save($c);
            }

        // Attach Images
        if ($request['selected_images'])
            foreach ($request['selected_images'] as $image){
                $im = Image::find($image);
                $article->images()->save($im);
            }
        
        return $this->show($article->id);
    }

    public function saveItems($request, $selection, $type, $collection){
        if ($request[$selection])
            foreach ($request[$selection] as $item){
                $ob = $type::find($item);
                $collection->save($ob);
            }
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

        // Clear Categories
        if ($article->categories)
            foreach( $article->categories as $c)
                $article->categories()->detach($c->id);

        // Attach Categories
        if ($request['selected_categories'])
            foreach ($request['selected_categories'] as $category){
                $c = Category::find($category);
                $article->categories()->save($c);
            }

        // Clear Images
        if ($article->images)
            foreach( $article->images as $im)
                $article->images()->detach($im->id);

        // Attach Images
        if ($request['selected_images'])
            foreach ($request['selected_images'] as $image){
                $im = Image::find($image);
                $article->images()->save($im);
            }

        $article->save();

        return $this->show($article->id);
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
