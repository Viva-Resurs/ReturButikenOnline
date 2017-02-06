<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'name',
        'desc',
        'price',
        'public',
        'publish_interval',
        'bidding_interval'
    ];

    public function creator()
    {
        return $this->belongsToMany(User::class, 'articles_creator');
    }

    public function contacts()
    {
        return $this->belongsToMany(User::class, 'articles_contact');
    }

    public function sections() {
        return $this->belongsToMany(Section::class,'articles_section');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'articles_category');
    }

    public function images() {
        return $this->belongsToMany(Image::class, 'articles_image');
    }

}
