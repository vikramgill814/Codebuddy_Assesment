<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    static $roles = [
        'Admin',
        'User'
    ];

    public function run()
    {
        foreach (self::$roles as $role) {
            DB::table('roles')->insert([
                'role' => $role,
            ]);
        }
    }
}
