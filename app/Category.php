<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name'
    ];

   /**
     * Get articles related to the category.
     *
     * @return articles  
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'articles_category');
    }

}
