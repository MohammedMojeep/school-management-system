<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Teacher extends Model {
    protected $fillable = ['user_id', 'phone_number', 'address'];
    public function user() { return $this->belongsTo(User::class); }
    public function subjects() { return $this->hasMany(Subject::class); }
}