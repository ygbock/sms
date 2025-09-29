<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $table = 'classes';
    protected $fillable = ['name', 'level'];

    public function sections() { return $this->hasMany(Section::class, 'class_id'); }
}
