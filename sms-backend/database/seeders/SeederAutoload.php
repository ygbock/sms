<?php
// Helper to ensure older artisan invocations can find the DatabaseSeeder in global namespace
if (!class_exists('\DatabaseSeeder') && class_exists('\Database\Seeders\DatabaseSeeder')) {
    class_alias('\Database\Seeders\DatabaseSeeder', '\\DatabaseSeeder');
}
