<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'name', 'label'
    ];

   /**
     * Get users related to the role.
     *
     * @return users    
     */
    public function users() {
        return $this->belongsToMany(User::class, 'roles_user');
    }

}
