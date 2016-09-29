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

    public function category()
    {
        return $this->belongsTo(Category::class, 'articles_category');
    }

    public function images() {
        return $this->hasMany(Image::class);
    }

}
