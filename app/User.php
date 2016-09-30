<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Role;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'fullname'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Article association
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles() {
        return $this->hasMany(Article::class);
    }




    /**
     * Role association
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role() {
        return $this->belongsTo(Role::class, 'roles_user');
    }

    /**
     * Check if the User have a given role
     *
     * @param $role
     * @return bool
     */
    public function hasRole($role) {
        if (is_string($role))
            return $this->role->contains('name', $role);

        return !!$role->intersect($this->role)->count();
    }

    /**
     * Assign a given role to the User
     *
     * @param $role
     * @return Model
     */
    public function assignRole($role) {
        
        return $this->role()->save(
            Role::whereName($role)->firstOrFail()
        );

        //if (is_string($role)) {
            //$role_model = Role::where('name', $role)->first();
            
            //$role_model->users()->save($this);

            //var_dump($role_model);
            //$this->role()->save($role_model);
            //return $this->role()->save(
            //    Role::whereName($role)->firstOrFail();
            //);
        //}

        //return $this->role()->save($role);
    }

    /**
     * Get an array of the Users roles
     *
     * @return array
     */
    public function getRole() {
        return $this->role;
    }

}
