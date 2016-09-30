<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Role;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTablesSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}

class RolesTablesSeeder extends Seeder {
    public function run() {
        DB::table('roles')->delete();
        DB::table('roles_user')->delete();

        // Admin
        $admin = Role::create([
            'name'  => 'admin',
            'label' => 'auth.administrator',
        ]);
    }
}

class UsersTableSeeder extends Seeder {
    public function run() {
        DB::table('users')->delete();

        $admin = User::create([
            'name'     => config('returbutiken.settings.users.administrator.username'),
            'password' => config('returbutiken.settings.users.administrator.password')
        ]);

        $admin->assignRole('admin');
    }
}
