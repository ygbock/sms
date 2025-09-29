<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('teacher_id')->constrained('users');
            $table->foreignId('section_id')->constrained('sections');
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'excused']);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('attendance');
    }
};
