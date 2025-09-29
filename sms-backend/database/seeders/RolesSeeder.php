<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RolesSeeder extends Seeder {
    public function run(): void {
        $roles = [
            ['name' => 'Admin', 'permissions' => json_encode(['*'])],
            ['name' => 'Teacher', 'permissions' => json_encode(['manage_attendance', 'view_students'])],
            ['name' => 'Student', 'permissions' => json_encode(['view_grades', 'view_attendance'])],
            ['name' => 'Parent', 'permissions' => json_encode(['view_child_records'])],
        ];

        foreach ($roles as $role) {
            if (!DB::table('roles')->where('name', $role['name'])->exists()) {
                DB::table('roles')->insert($role);
            }
        }
    }
}