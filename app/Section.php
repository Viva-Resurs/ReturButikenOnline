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

    public function users()
    {
        return $this->belongsToMany(User::class, 'sections_user');
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'articles_section');
    }
}
