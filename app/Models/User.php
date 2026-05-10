<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable {
    use Notifiable;
    protected $fillable = ['name', 'email', 'password', 'role_id'];
    protected $hidden = ['password', 'remember_token'];
    protected function casts(): array { return ['email_verified_at' => 'datetime', 'password' => 'hashed']; }
    public function role() { return $this->belongsTo(Role::class); }
    public function teacher() { return $this->hasOne(Teacher::class); }
    public function student() { return $this->hasOne(Student::class); }
    public function isAdmin() { return $this->role->name === 'Admin'; }
    public function isTeacher() { return $this->role->name === 'Teacher'; }
    public function isStudent() { return $this->role->name === 'Student'; }
}