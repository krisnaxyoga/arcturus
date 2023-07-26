<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create([
            'role_name' => 'superadmin',
        ]);

        Role::create([
            'role_name' => 'vendor',
        ]);

        Role::create([
            'role_name' => 'agent',
        ]);
        
        User::factory(5)->create();
    }
}
