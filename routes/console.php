<?php

use Illuminate\Foundation\Inspiring;
//use App\Console\Commands\GenerateArticles;

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

Artisan::command('generate:articles', function () {
    factory(App\Article::class, 10)->create();
})->describe('Generate Articles');
