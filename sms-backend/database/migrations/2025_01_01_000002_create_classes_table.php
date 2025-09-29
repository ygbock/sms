<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
public function up(): void {
Schema::create('classes', function (Blueprint $table) {
$table->id();
$table->string('name');
$table->enum('level', ['primary', 'junior', 'senior']);
$table->timestamps();
});
}
public function down(): void {
Schema::dropIfExists('classes');
}
};