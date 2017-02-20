<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use App\Section;
use App\Image;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Http\Requests;

class UserController extends Controller
{
    /**
     * List users.
     *
     * Admin can list all users
     * Section-admins & publishers can list users within their sections
     *
     * @return Array
     */
    public function index(){

        $me = Auth::user();

        if (!$me)
            abort(401,'Not logged in');

        $results = [];
        foreach (User::all() as $user){
            $u = [
                'id' => $user->id,
                'name' => $user->name,
                'fullname' => $user->fullname,
                'email' => $user->email,
                'phone' => $user->phone,
                'roles' => [],
                'sections' => [],
                'images' => []
            ];

            foreach ($user->roles as $role)
                array_push($u['roles'],[
                    'id' => $role->id,
                    'name' => $role->name
                ]);

            foreach ($user->sections as $section)
                array_push($u['sections'],[
                    'id' => $section->id,
                    'name' => $section->name
                ]);

            foreach ($user->images as $image)
                array_push($u['images'], [
                    'id' => $image->id,
                    'name' => $image->name,
                    'original_name' => $image->original_name,
                    'order' => $image->order,
                    'path' => $image->path . '?',
                    'thumb_path' => $image->thumb_path . '?'
                ]);

            if ($me->hasRole('admin') || $this->sameSections($me,$u))
                array_push($results,$u);
        }

        return $results;
    }

    /**
     * Show a user.
     *
     * Admin can show any user
     * Section-admin can show users within their sections
     * Publishers can only show themselves
     *
     * @return Array
     */
    public function show($id){

        $me = Auth::user();

        if (!$me)
            abort(401,'Not logged in');

        $user = User::find($id);

        if (!$user)
            abort(404, 'Could not find user');

        if (!$me->hasRole('admin') && !$me->hasRole('sectionadmin') && $user->id!=$me->id)
            abort(401,'Not allowed to list user');

        $result = [
            'id' => $user->id,
            'name' => $user->name,
            'fullname' => $user->fullname,
            'email' => $user->email,
            'phone' => $user->phone,
            'roles' => [],
            'sections' => [],
            'images' => []
        ];

        foreach ($user->roles as $role)
            array_push($result['roles'],[
                'id' => $role->id,
                'name' => $role->name
            ]);

        foreach ($user->sections as $section)
            array_push($result['sections'],[
                'id' => $section->id,
                'name' => $section->name
            ]);

        foreach ($user->images()->orderBy('order')->get() as $image)
            array_push($result['images'], [
                'id' => $image->id,
                'name' => $image->name,
                'original_name' => $image->original_name,
                'order' => $image->order,
                'path' => $image->path . '?',
                'thumb_path' => $image->thumb_path . '?'
            ]);

        return $result;
    }

    /**
     * Create a new user.
     *
     * Admin can create any user
     * Section-admin can create publishers within their sections
     * Publishers cant create anything
     *
     * @return Array
     */
    public function store(Request $request){

        $me = Auth::user();

        if (!$me)
            abort(401,'Not logged in');

        if (!$me->hasRole('admin') && !$me->hasRole('sectionadmin') )
            abort(401,'Not allowed to create users');

        $user = new User([
            'name' => $request['name'],
            'fullname' => ($request->has('fullname')) ? $request['fullname'] : '',
            'phone' => ($request->has('phone')) ? $request['phone'] : '',
            'email' => ($request->has('email')) ? $request['email'] : '',
            'password' => bcrypt($request['password'])
        ]);

        $user->save();

        // Admin can create any type of user
        if ($me->hasRole('admin')) {

            // Attach Roles
            if ($request['roles'])
                foreach ($request['roles'] as $role){
                    $r = Role::find($role['id']);
                    if ($r)
                        $user->roles()->save($r);
                }

            // Attach Sections
            if ($request['sections'])
                foreach ($request['sections'] as $section){
                    $s = Role::find($section['id']);
                    if ($s)
                        $user->sections()->save($s);
                }

        }

        // Section-admin can create publishers within their sections
        if ($me->hasRole('sectionadmin')){

            // Attach Roles
            $r = Role::where('name', 'publisher')->first();
            if ($r)
                $user->roles()->save($r);

            // Attach Sections
            foreach ($me['sections'] as $section)
                $user->sections()->save($section);

        }

        // Attach Images
        if ($request['images'])
            foreach ($request['images'] as $image){
                $im = Image::find($image['id']);
                $user->images()->save($im);
            }

        return $this->show($user->id);
    }

    /**
     * Update a user.
     *
     * Admin can change any user
     * Section-admin can edit publishers info within their sections
     * Publishers can only update their info
     *
     * @return Array
     */
    public function update(Request $request, $id){

        $me = Auth::user();

        if (!$me)
            abort(401,'Not logged in');

        $user = User::find($id);

        if (!$user)
            abort(404);

        if (
            !$me->hasRole('admin') &&
            $user->id!=$me->id &&
            ($me->hasRole('sectionadmin') && !$this->sameSections($me,$user))
        )
            abort(401,'Not allowed to update user');

        if ($request->has('name') && $request['name']!='')
            $user->name = $request['name'];

        if ($request->has('fullname') && $request['fullname']!='')
            $user->fullname = $request['fullname'];

        if ($request->has('email') && $request['email']!='')
            $user->email = $request['email'];

        if ($request->has('phone') && $request['phone']!='')
            $user->phone = $request['phone'];

        if ($request->has('password') && $request['password']!='')
            $user->password = bcrypt($request['password']);

        // Clear removed Images
        foreach($user->images as $old_image){
            $keep = false;
            if ($request['images'])
                foreach ($request['images'] as $image)
                    if ($old_image->id == $image['id'])
                        $keep = true;
            if (!$keep){
                // Attached image could not be found in selection, detach it
                $user->images()->detach($old_image->id);
                // Delete it
                $old_image->delete();
            }
        }

        // Attach new Images
        foreach ($request['images'] as $image){
            $new = true;
            if ($user->images)
                foreach( $user->images as $old_image)
                    if ($old_image->id == $image['id'])
                        $new = false;
            if ($new) {
                // Image could not be found in currently attached images
                $image = Image::find($image['id']);
                // Attach it
                $user->images()->save($image);
            }
        }

        // Update Images order
        foreach($request['images'] as $image){
            $user_image = Image::find($image['id']);
            $user_image->order = $image['order'];
            $user_image->save();
        }

        // Admin can change roles
        if ($me->hasRole('admin')) {
            // Clear Roles
            foreach($user->roles as $role)
                $user->roles()->detach($role->id);

            // Attach Roles
            if ($request['roles'])
                foreach ($request['roles'] as $role){
                    $r = Role::find($role['id']);
                    if ($r)
                        $user->roles()->save($r);
                }
        }

        // Admin can change users sections
        if ($me->hasRole('admin')) {
            // Clear Sections
            foreach($user->sections as $section)
                $user->sections()->detach($section->id);

            // Attach Sections
            if ($request['sections'])
                foreach ($request['sections'] as $section){
                    $s = Section::find($section['id']);
                    if ($s)
                        $user->sections()->save($s);
                }
        }

        $user->save();

        return $this->show($user->id);
    }

    /**
     * Remove a user.
     *
     * Admin can remove any user (except itself)
     * Section-admin can remove publishers within their sections
     * Publishers cant remove anything
     *
     * @return void
     */
    public function destroy($id){

        $me = Auth::user();

        if (!$me)
            abort(401,'Not logged in');

        $user = User::find($id);

        if (!$user)
            abort(404);

        if ($user->id==$me->id)
            abort(401,'No suicide');

        if ( !$me->hasRole('admin') && !$me->hasRole('sectionadmin') )
            abort(401,'Not allowed to remove user');

        if ($me->hasRole('sectionadmin'))
            if ( !$this->sameSections($me,$user) || !$user->hasRole('publisher') )
                abort(401,'Not allowed to remove user');

        $user->delete();
    }

    /**
     * Check if two users share a section
     */
    public function sameSections($user1, $user2){
        foreach ($user1['sections'] as $sectionA) {
            foreach ($user2['sections'] as $sectionB) {
                if ($sectionA && $sectionB)
                    if ($sectionA['id'] == $sectionB['id'])
                        return true;
            }
        }
        return false;
    }

    /**
     * Get currently authorized user
     */
    public function me(){

        $me = Auth::user();

        if (!$me)
            abort(401,'Not logged in');

        $result = [
            'id' => $me->id,
            'name' => $me->name,
            'fullname' => $me->fullname,
            'email' => $me->email,
            'phone' => $me->phone,
            'roles' => [],
            'sections' => [],
            'images' => []
        ];

        foreach ($me->roles as $role)
            array_push($result['roles'],[
                'id' => $role->id,
                'name' => $role->name
            ]);

        foreach ($me->images as $image)
            array_push($result['images'], [
                'id' => $image->id,
                'name' => $image->name,
                'original_name' => $image->original_name,
                'path' => $image->path . '?',
                'thumb_path' => $image->thumb_path . '?'
            ]);

        foreach ($me->sections as $section)
            array_push($result['sections'],[
                'id' => $section->id,
                'name' => $section->name
            ]);

        return [
            'user' => $result,
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
     * Get contacts
     */
    public function contacts(){
        $results = [];

        foreach ($this->index() as $user){
            $u = User::find($user['id']);
            if ($u->hasRole('sectionadmin'))
                array_push($results,$user);
        }

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
