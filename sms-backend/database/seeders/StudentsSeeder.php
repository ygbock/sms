<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentsSeeder extends Seeder {
    public function run(): void {
        $section = DB::table('sections')->first();
        if (!$section) return;

        $students = [
            ['name' => 'Student One', 'email' => 'student1@example.com', 'admission_no' => 'ADM001', 'dob' => '2014-01-01'],
            ['name' => 'Student Two', 'email' => 'student2@example.com', 'admission_no' => 'ADM002', 'dob' => '2014-06-01'],
        ];

        foreach ($students as $s) {
            $existingUser = DB::table('users')->where('email', $s['email'])->first();
            if (!$existingUser) {
                $uid = DB::table('users')->insertGetId([
                    'name' => $s['name'],
                    'email' => $s['email'],
                    'password' => Hash::make('password'),
                    'role_id' => 3,
                ]);
                DB::table('students')->insert([
                    'user_id' => $uid,
                    'admission_no' => $s['admission_no'],
                    'dob' => $s['dob'],
                    'section_id' => $section->id,
                ]);
            }
        }
    }
}
