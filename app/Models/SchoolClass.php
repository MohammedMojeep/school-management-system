<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SchoolClass extends Model {
    protected $table = 'school_classes';
    protected $fillable = ['name', 'stage_id'];
    public function stage() { return $this->belongsTo(Stage::class); }
    public function students() { return $this->hasMany(Student::class); }
    public function subjects() { return $this->hasMany(Subject::class); }
}