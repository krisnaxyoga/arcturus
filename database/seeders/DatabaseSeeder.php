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


        \App\Models\Setting::create([
            'company_name' => 'Hotel Booking System Inc.',
            'business_permit_number' => '123456789',
            'description' => 'Hotel Booking System adalah sebuah aplikasi reservasi hotel yang dibangun menggunakan Laravel dan VueJS.',
            'address_line1' => 'Jl. Jend. Sudirman No.1',
            'address_line2' => 'Jakarta Pusat',
            'country' => 'Indonesia',
            'state' => 'DKI Jakarta',
            'city' => 'Jakarta',
            'telephone' => '081234567890',
            'fax' => '081234567891',
            'email' => 'admin@hotelbookingsystem.com',
            'zipcode' => '12345',
            'logo_image' => 'logo.png',
            'url_logo_image' => 'https://hotelbookingsystem.com/logo.png',
            'url_website' => 'https://hotelbookingsystem.com',
        ]);
    }
}