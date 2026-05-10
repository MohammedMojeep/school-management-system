<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Stage extends Model {
    protected $fillable = ['name', 'notes'];
    public function schoolClasses() { return $this->hasMany(SchoolClass::class); }
    public function students() { return $this->hasMany(Student::class); }
}