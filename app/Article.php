<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'name',
        'desc',
        'public',
        'publish_interval',
        'bidding_interval'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'articles_owner');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'articles_category');
    }

    public function images() {
        return $this->belongsToMany(Image::class, 'articles_image');
    }

}
