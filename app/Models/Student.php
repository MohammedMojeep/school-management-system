<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Student extends Model {
    protected $fillable = ['user_id', 'student_id', 'stage_id', 'school_class_id', 'date_of_birth', 'gender', 'parent_phone', 'address'];
    public function user() { return $this->belongsTo(User::class); }
    public function stage() { return $this->belongsTo(Stage::class); }
    public function schoolClass() { return $this->belongsTo(SchoolClass::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }
}