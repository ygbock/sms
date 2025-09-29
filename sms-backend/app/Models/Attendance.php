<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['student_id','teacher_id','section_id','date','status'];

    public function student() { return $this->belongsTo(Student::class); }
    public function teacher() { return $this->belongsTo(User::class, 'teacher_id'); }
    public function section() { return $this->belongsTo(Section::class); }
}
