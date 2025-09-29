<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();
        if ($students->isEmpty()) return;

        // create attendance for the past 7 days for each student
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $dates[] = Carbon::today()->subDays($i)->toDateString();
        }

        foreach ($students as $student) {
            foreach ($dates as $date) {
                Attendance::create([
                    'student_id' => $student->id,
                    'section_id' => $student->section_id,
                    'teacher_id' => 1, // seed admin/teacher as recorder
                    'date' => $date,
                    'status' => (rand(0,10) > 1) ? 'present' : 'absent',
                ]);
            }
        }
    }
}
