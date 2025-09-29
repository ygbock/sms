<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['user_id', 'admission_no', 'dob', 'section_id'];

    public function user() { return $this->belongsTo(User::class); }
    public function section() { return $this->belongsTo(Section::class); }
    public function attendance() { return $this->hasMany(Attendance::class); }

    // guardians/parents relation
    public function guardians() { return $this->belongsToMany(User::class, 'guardian_student', 'student_id', 'guardian_id'); }
}
