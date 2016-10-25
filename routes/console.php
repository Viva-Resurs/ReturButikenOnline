<?php

use Illuminate\Foundation\Inspiring;

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

Artisan::command('articles:generate {nr=1}', function ($nr) {
    factory(App\Article::class, (int) $nr)->create();
    echo "Generated $nr articles.";
})->describe('Generate Articles [nr] - you can specify how many by passing nr');

Artisan::command('articles:clear', function () {
    DB::table('articles')->delete();
    echo "Cleared all articles";
})->describe('Clear Articles');
