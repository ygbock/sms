<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void {
Schema::create('sections', function (Blueprint $table) {
$table->id();
$table->string('name');
$table->foreignId('class_id')->constrained('classes');
$table->foreignId('teacher_id')->nullable()->constrained('users');
$table->timestamps();
});
}
public function down(): void {
Schema::dropIfExists('sections');
}
};