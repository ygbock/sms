<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuardianSeeder extends Seeder
{
    public function run(): void
    {
        // Attach the first Parent/Guardian user to the first student for local dev convenience
        $parent = DB::table('users')->where('role_id', function($q) {
            $q->select('id')->from('roles')->where('name', 'Parent')->limit(1);
        })->first();

        $student = DB::table('students')->first();

        if ($parent && $student) {
            if (!DB::table('guardian_student')->where('guardian_id', $parent->id)->where('student_id', $student->id)->exists()) {
                DB::table('guardian_student')->insert([ 'guardian_id' => $parent->id, 'student_id' => $student->id, 'created_at' => now(), 'updated_at' => now() ]);
            }
        }
    }
}
