<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table = 'sections';

    protected $fillable = [
        'name',
        'desc',
    ];

    public function admins()
    {
        return $this->belongsToMany(User::class, 'sections_admins');
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'sections_articles');
    }
}
