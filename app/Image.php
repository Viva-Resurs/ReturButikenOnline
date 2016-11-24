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

    public function article()
    {
        return $this->hasMany(Article::class);
    }
}
