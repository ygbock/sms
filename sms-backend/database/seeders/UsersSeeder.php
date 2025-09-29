<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class UsersSeeder extends Seeder {
    public function run(): void {
        if (!DB::table('users')->where('email', 'admin@example.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
            ]);
        }
    }
}