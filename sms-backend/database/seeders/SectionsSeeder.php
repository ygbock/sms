<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionsSeeder extends Seeder {
    public function run(): void {
        $class1 = DB::table('classes')->where('name', 'Primary 1')->first();
        $class2 = DB::table('classes')->where('name', 'Primary 2')->first();

        if (!$class1) return; // nothing to seed

        $items = [
            ['name' => 'A', 'class_id' => $class1->id, 'teacher_id' => null],
            ['name' => 'B', 'class_id' => $class1->id, 'teacher_id' => null],
        ];

        if ($class2) {
            $items[] = ['name' => 'A', 'class_id' => $class2->id, 'teacher_id' => null];
        }

        foreach ($items as $it) {
            if (!DB::table('sections')->where('name', $it['name'])->where('class_id', $it['class_id'])->exists()) {
                DB::table('sections')->insert($it);
            }
        }
    }
}
