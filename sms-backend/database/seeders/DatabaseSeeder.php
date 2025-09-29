<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            // Core seeders + sample data for local development
            SampleDataSeeder::class,
        ]);
    }
}