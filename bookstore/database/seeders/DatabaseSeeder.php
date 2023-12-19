<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        //Seed Roles
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'User']);

        // Seed Admin User
        $adminRole = Role::where('name', 'Admin')->first();
        User::create([
            'name' => 'System Admin',
            'email' => 'sysadmin@soft.com',
            'password' => Hash::make('Test.12345'),
            'role_id' => $adminRole->id,
        ]);
    }
}
