<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassesSeeder extends Seeder {
    public function run(): void {
        $items = [
            ['name' => 'Primary 1', 'level' => 'primary'],
            ['name' => 'Primary 2', 'level' => 'primary'],
            ['name' => 'Junior 1', 'level' => 'junior'],
        ];

        foreach ($items as $it) {
            if (!DB::table('classes')->where('name', $it['name'])->exists()) {
                DB::table('classes')->insert($it);
            }
        }
    }
}
