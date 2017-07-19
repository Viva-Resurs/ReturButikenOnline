<?php

use Illuminate\Foundation\Inspiring;
use Carbon\Carbon;
use App\Article;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

/*
 *  Randomly generates a number of articles.
 */
Artisan::command('articles:generate {nr=1}', function ($nr) {
    factory(App\Article::class, (int) $nr)->create();
    echo "Generated $nr articles.";
})->describe('Generate Articles [nr] - you can specify how many by passing nr');

/*
 *  Removes all articles.
 */
Artisan::command('articles:clear', function () {
    DB::table('articles')->delete();
    echo "Cleared all articles";
})->describe('Clear Articles');

/*
 *  Removes articles older than a specified number of weeks 
 */
Artisan::command('articles:remove_old {weeks=2}', function($weeks) {    
    $today = Carbon::now();
    $old_articles = array();
    
    foreach (Article::all() as $article){       
        if (($article->publish_interval != '') && ($article->active == false)) {
            $parts = explode('|', $article->publish_interval);
            if (count($parts) == 2){                
                $end = Carbon::createFromFormat('Y-m-d H:i:s', trim($parts[1]));        
                if ($today->subWeeks($weeks)->gt($end))
                    array_push($old_articles, $article->id);
            }
        }
    }   

    foreach($old_articles as $article_id){
        $article = Article::find($article_id);
        $article->delete();
    }
   
})->describe('Removes articles older than 2 months');
