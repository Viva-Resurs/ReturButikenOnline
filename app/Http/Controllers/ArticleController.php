<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Image;
use App\User;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests;

class ArticleController extends Controller
{
    public function index(Request $request){

        $user = Auth::user();

        $result = [];
        foreach (Article::all() as $article){
            if ($user && $user->hasRole('admin') || $user && $user->inSection($article->sections) || !$user && $article->public==1)
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

    public function show($id){

        $user = Auth::user();

        $article = Article::find($id);

        if (!$article)
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

    public function store(Request $request){

        $user = Auth::user();

        if (!$user)
            abort(401,'Not allowed to create articles');

        $article = new Article([
            'name' => ($request->has('name')) ? $request['name'] : '',
            'desc' => ($request->has('desc')) ? $request['desc'] : '',
            'price' => ($request->has('price')) ? $request['price'] : '',
            'public' => $request['public'] || false,
            'publish_interval' => ($request->has('publish_interval')) ? $request['publish_interval'] : '',
            'bidding_interval' => ($request->has('bidding_interval')) ? $request['bidding_interval'] : ''
        ]);

        $article->save();

        // Attach Creator
        $article->creator()->save( User::find($user->id) );

        // Attach Categories
        if ($request['categories'])
            foreach ($request['categories'] as $category){
                $c = Category::find($category['id']);
                if ($c)
                    $article->categories()->save($c);
            }

        // Attach Images
        if ($request['images'])
            foreach ($request['images'] as $image){
                $im = Image::find($image['id']);
                $article->images()->save($im);
            }

        // Attach Contact & Section
        if ($request['contacts'])
            foreach ($request['contacts'] as $contact){
                $u = User::find($contact['id']);
                $article->contacts()->save($u);
                foreach ($u->sections as $section)
                    $article->sections()->save($section);
            }

        return $this->show($article->id);
    }

    public function update(Request $request, $id){

        $user = Auth::user();

        if (!$user)
            abort(401,'Not allowed to update articles');

        $article = Article::find($id);

        if (!$article)
            abort(404);

        if (
            !$user->hasRole('admin')
            &&
            $user->id!=$article->creator()->first()->id
            &&
            !$user->sections->find($article->sections->first()->id)
        )
            abort(401,'Not allowed to update article');

        if ($request->has('name') && $request['name']!='')
            $article->name = $request['name'];

        if ($request->has('desc') && $request['desc']!='')
            $article->desc = $request['desc'];

        if ($request->has('price') && $request['price']!='')
            $article->price = $request['price'];

        $article->public = $request['public'] || 0;

        $article->publish_interval = $request['publish_interval'];

        $article->bidding_interval = $request['bidding_interval'];

        // Clear Categories
        if ($article->categories)
            foreach( $article->categories as $c)
                $article->categories()->detach($c->id);

        // Attach Categories
        if ($request['categories'])
            foreach ($request['categories'] as $category){
                $c = Category::find($category['id']);
                $article->categories()->save($c);
            }

        // Clear removed Images
        foreach($article->images as $old_image){
            $keep = false;
            if ($request['images'])
                foreach ($request['images'] as $image)
                    if ($old_image->id == $image['id'])
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
        foreach ($request['images'] as $image){
            $new = true;
            if ($article->images)
                foreach( $article->images as $old_image)
                    if ($old_image->id == $image['id'])
                        $new = false;
            if ($new) {
                // Image could not be found in currently attached images
                $image = Image::find($image['id']);
                // Attach it
                $article->images()->save($image);
            }
        }

        // Update Images order
        foreach($request['images'] as $image){
            $article_image = Image::find($image['id']);
            $article_image->order = $image['order'];
            $article_image->save();
        }

        // Clear Contacts
        if ($article->contacts)
            foreach( $article->contacts as $u)
                $article->contacts()->detach($u->id);
        // Clear Sections
        if ($article->sections)
            foreach( $article->sections as $s)
                $article->sections()->detach($s->id);

        // Attach Contacts & Sections
        if ($request['contacts'])
            foreach ($request['contacts'] as $contact){
                $u = User::find($contact['id']);
                $article->contacts()->save($u);
                foreach( $u->sections as $section){
                    $article->sections()->save($section);
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

        if (
            !$user->hasRole('admin')
            &&
            $user->id!=$article->creator()->first()->id
            &&
            !$user->sections->find($article->sections->first()->id)
        )
            abort(401,'Not allowed to remove article');

        $article->delete();
    }

}
