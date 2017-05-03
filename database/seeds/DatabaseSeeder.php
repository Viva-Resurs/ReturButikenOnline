<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Role;
use App\Section;
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
        $this->call(SectionTableSeeder::class);
        $this->call(UsersTableSeeder::class);

    }
}

class RolesTablesSeeder extends Seeder {
    public function run() {
        DB::table('roles')->delete();
        DB::table('roles_user')->delete();

        // Admin
        Role::create([
            'name'  => 'admin',
            'label' => 'auth.administrator',
        ]);

        // Section Admin
        Role::create([
            'name'  => 'sectionadmin',
            'label' => 'auth.section_administrator',
        ]);

        // Publisher
        Role::create([
            'name'  => 'publisher',
            'label' => 'auth.publisher',
        ]);
    }
}

class SectionTableSeeder extends Seeder {
    public function run() {
        DB::table('sections')->truncate();
        $section = Section::create([
           'name' => config('returbutiken.settings.sections.section1.name'),
           'name_sv' => config('returbutiken.settings.sections.section1.name_sv'),
           'desc' => config('returbutiken.settings.sections.section1.desc')
        ]);
        $section->save();
    }
}

class UsersTableSeeder extends Seeder {
    public function run() {
        DB::table('users')->truncate();

        $admin = User::create([
            'name'     => config('returbutiken.settings.users.administrator.username'),
            'email'    => config('returbutiken.settings.users.administrator.email'),
            'fullname' => config('returbutiken.settings.users.administrator.fullname'),
            'phone'    => config('returbutiken.settings.users.administrator.phone'),
            'password' => Hash::make(config('returbutiken.settings.users.administrator.password'))
        ]);

        $admin->assignRole('admin');

        $sectionadmin = User::create([
            'name'     => config('returbutiken.settings.users.sectionadmin.username'),
            'email'    => config('returbutiken.settings.users.sectionadmin.email'),
            'fullname' => config('returbutiken.settings.users.sectionadmin.fullname'),
            'phone'    => config('returbutiken.settings.users.sectionadmin.phone'),
            'password' => Hash::make(config('returbutiken.settings.users.sectionadmin.password'))
        ]);

        $admin->assignRole('admin');

        $sectionadmin->assignRole('sectionadmin');
        $sectionadmin->assignSection('IT-department');

    }
}
