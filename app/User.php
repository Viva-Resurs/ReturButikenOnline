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
     * Role association
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function roles() {
        return $this->belongsToMany(Role::class,'roles_user');
    }

    /**
     * Section association
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sections() {
        return $this->belongsToMany(Section::class,'sections_user');
    }

    /**
     * Image association
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function images() {
        return $this->belongsToMany(Image::class,'images_user');
    }

    /**
     * Check if the User is in a given section
     *
     * @param $section
     * @return bool
     */
    public function inSection($section) {
        if (is_string($section))
            return $this->sections->contains('name', $section);

        return !!$section->intersect($this->sections)->count();
    }

    /**
     * Check if the User have a given role
     *
     * @param $role
     * @return bool
     */
    public function hasRole($role) {
        if (is_string($role))
            return $this->roles->contains('name', $role);

        return !!$role->intersect($this->roles)->count();
    }

    /**
     * Assign a given role to the User
     *
     * @param $role
     * @return Model
     */
    public function assignRole($role) {

        if (is_string($role))
            return $this->roles()->save(
                Role::whereName($role)->firstOrFail()
            );

        else
            return $this->roles()->save($role);
    }

    
    /**
     * Assign a given section to the User
     *
     * @param $section
     * @return Model
     */
    public function assignSection($section) {

        if (is_string($section))
            return $this->sections()->save(
                Section::whereName($section)->firstOrFail()
            );

        else
            return $this->sections()->save($section);
    }

    /**
     * Get an array of the Users roles
     *
     * @return array
     */
    public function getRoles() {
        $roles_array = [];

        foreach ($this->roles as $role)
            array_push($roles_array, $role->name);

        return $roles_array;
    }

}
