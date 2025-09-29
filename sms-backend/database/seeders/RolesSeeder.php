namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RolesSeeder extends Seeder {
public function run(): void {
DB::table('roles')->insert([
['name' => 'Admin', 'permissions' => json_encode(['*'])],
['name' => 'Teacher', 'permissions' => json_encode(['manage_attendance', 'view_students'])],
['name' => 'Student', 'permissions' => json_encode(['view_grades', 'view_attendance'])],
['name' => 'Parent', 'permissions' => json_encode(['view_child_records'])],
]);
}
}