<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@codebuddy.com',
            'password'=>Hash::make('123456'),
            'role_id'=>1
         ]);
         \App\Models\User::create([
            'name' => 'User1',
            'email' => 'user1@codebuddy.com',
            'password'=>Hash::make('123456'),
            'role_id'=>2
         ]);
    }
}
