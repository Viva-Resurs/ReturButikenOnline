<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Image;

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
            array_push($result['selected_images'], [
                'id' => $image->id,
                'name' => $image->name,
                'path' => $image->path,
                'thumb_path' => $image->thumb_path
            ]);

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
                if ($c)
                    $article->categories()->save($c);
            }

        // Attach Images
        if ($request['selected_images'])
            foreach ($request['selected_images'] as $image){
                $im = Image::find($image['id']);
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

        // Clear removed Images
        foreach($article->images as $old_image){
            $keep = false;
            if ($request['selected_images'])
                foreach ($request['selected_images'] as $selected_image)
                    if ($old_image->id == $selected_image['id'])
                        $keep = true;
            if (!$keep){
                // Attached image could not be found in selection, detach it
                $article->images()->detach($old_image->id);
                // Check if image is used on other articles
                if (Image::withCount('articles')->find($old_image->id)->articles_count<1){
                    // No article is using the image, delete it
                    $old_image->delete();
                }
            }
        }

        // Attach new Images
        foreach ($request['selected_images'] as $selected_image){
            $new = true;
            if ($article->images)
                foreach( $article->images as $old_image)
                    if ($old_image->id == $selected_image['id'])
                        $new = false;
            if ($new) {
                // Image could not be found in currently attached images
                $image = Image::find($selected_image['id']);
                // Attach it
                $article->images()->save($image);
            }
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
