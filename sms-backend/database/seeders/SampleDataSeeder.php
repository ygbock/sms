<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder {
    public function run(): void {
        $this->call([
            RolesSeeder::class,
            UsersSeeder::class,
            ClassesSeeder::class,
            SectionsSeeder::class,
            StudentsSeeder::class,
            AttendanceSeeder::class,
            GuardianSeeder::class,
        ]);
    }
}
