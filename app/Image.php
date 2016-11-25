<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';

    protected $fillable = [
        'name',
        'original_name',
        'path',
        'thumb_path'
    ];

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'articles_image');
    }
}
