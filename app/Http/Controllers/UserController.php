<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests;

class UserController extends Controller
{
    public function index(){

        $me = Auth::user();

        if (!$me || !$me->hasRole('admin'))
            abort(401,'Not allowed to list users');

        $results = [];
        foreach (User::all() as $user){
            $u = [
                'id' => $user->id,
                'name' => $user->name,
                'roles' => []
            ];

            foreach ($user->roles as $role)
                array_push($u['roles'],$role->name);

            array_push($results,$u);
        }

        return $results;
    }

    public function show($id){

        $me = Auth::user();

        if (!$me || !$me->hasRole('admin'))
            abort(401,'Not allowed to show users');

        $user = User::find($id);

        if (!$user)
            abort(404, 'Could not find user');

        $result = [
            'id' => $user->id,
            'name' => $user->name,
            'fullName' => $user->fullName,
            'email' => $user->email,
            'phone' => $user->phone,
            'selected_roles' => []
        ];

        foreach ($user->roles as $role)
            array_push($result['selected_roles'],$role->id);

        return $result;
    }

    public function store(Request $request){

        $me = Auth::user();

        if (!$me)
            abort(401,'Not allowed to create users');

        $user = new User([
            'name' => $request['name'],
            'fullName' => ($request->has('fullName')) ? $request['fullName'] : '',
            'phone' => ($request->has('phone')) ? $request['phone'] : '',
            'email' => ($request->has('email')) ? $request['email'] : '',
            'password' => bcrypt($request['password'])
        ]);

        $user->save();

        // Attach Roles
        if ($request['selected_roles'])
            foreach ($request['selected_roles'] as $role){
                $r = Role::find($role);
                if ($r)
                    $user->roles()->save($r);
            }

        return $this->show($user->id);
    }

    public function update(Request $request, $id){

        $me = Auth::user();

        if (!$me)
            abort(401,'Not allowed to list users');

        $user = User::find($id);

        if (!$user)
            abort(404);

        if ($user->id!=$me->id && !$me->hasRole('admin'))
            abort(401,'Not allowed to update users');

        if ($request->has('name') && $request['name']!='')
            $user->name = $request['name'];

        if ($request->has('fullName') && $request['fullName']!='')
            $user->fullName = $request['fullName'];

        if ($request->has('email') && $request['email']!='')
            $user->email = $request['email'];

        if ($request->has('phone') && $request['phone']!='')
            $user->phone = $request['phone'];

        if ($request->has('password') && $request['password']!='')
            $user->password = bcrypt($request['password']);

        // Admin can change roles
        if ($me->hasRole('admin')) {
            // Clear Roles
            foreach($user->roles as $role)
                $user->roles()->detach($role->id);

            // Attach Roles
            if ($request['selected_roles'])
                foreach ($request['selected_roles'] as $role){
                    $r = Role::find($role);
                    if ($r)
                        $user->roles()->save($r);
                }
        }

        $user->save();

        return $this->show($user->id);
    }

    public function destroy($id){

        $me = Auth::user();

        if (!$me)
            abort(401,'Not allowed to remove users');

        $user = User::find($id);

        if (!$user)
            abort(404);

        if ($user->id==$me->id)
            abort(401,'No suicide');

        $user->delete();
    }


    /**
     * Get currently authorized user
     */
    public function me(){
        return [
            'user' => Auth::user(),
            'token' => csrf_token()
        ];
    }

    /**
     * Get roles
     */
    public function rolesList(){
        $results = [];

        foreach (Role::all() as $role)
            array_push($results,[
                'id' => $role->id,
                'name' => $role->name
            ]);

        return $results;
    }

    /**
     * Get latest token
     */
    public function token(){
        return [
            'token' => csrf_token()
        ];
    }

}
