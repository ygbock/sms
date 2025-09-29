<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['name', 'class_id', 'teacher_id'];

    public function class() { return $this->belongsTo(SchoolClass::class, 'class_id'); }
    public function teacher() { return $this->belongsTo(User::class, 'teacher_id'); }
    public function students() { return $this->hasMany(Student::class); }
}
