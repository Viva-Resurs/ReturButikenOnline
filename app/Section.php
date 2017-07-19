<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table = 'sections';

    protected $fillable = [
        'name',
        'name_sv',        
        'desc',
    ];

    /**
     * Get users related to the section.
     *
     * @return users    
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'sections_user');
    }

    /**
     * Get articles related to the section.
     *
     * @return articles    
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'articles_section');
    }
}
