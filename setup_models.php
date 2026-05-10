<?php

$modelsPath = __DIR__ . '/app/Models/';

// delete all old models first
$files = glob($modelsPath . '*.php');
foreach ($files as $file) {
    unlink($file);
}

$models = [
    'Role.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Role extends Model {
    protected \$fillable = ['name'];
    public function users() { return \$this->hasMany(User::class); }
}
EOT,

    'User.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable {
    use Notifiable;
    protected \$fillable = ['name', 'email', 'password', 'role_id'];
    protected \$hidden = ['password', 'remember_token'];
    protected function casts(): array { return ['email_verified_at' => 'datetime', 'password' => 'hashed']; }
    public function role() { return \$this->belongsTo(Role::class); }
    public function teacher() { return \$this->hasOne(Teacher::class); }
    public function student() { return \$this->hasOne(Student::class); }
    public function isAdmin() { return \$this->role->name === 'Admin'; }
    public function isTeacher() { return \$this->role->name === 'Teacher'; }
    public function isStudent() { return \$this->role->name === 'Student'; }
}
EOT,

    'Stage.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Stage extends Model {
    protected \$fillable = ['name', 'notes'];
    public function schoolClasses() { return \$this->hasMany(SchoolClass::class); }
    public function students() { return \$this->hasMany(Student::class); }
}
EOT,

    'SchoolClass.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SchoolClass extends Model {
    protected \$table = 'school_classes';
    protected \$fillable = ['name', 'stage_id'];
    public function stage() { return \$this->belongsTo(Stage::class); }
    public function students() { return \$this->hasMany(Student::class); }
    public function subjects() { return \$this->hasMany(Subject::class); }
}
EOT,

    'Teacher.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Teacher extends Model {
    protected \$fillable = ['user_id', 'phone_number', 'address'];
    public function user() { return \$this->belongsTo(User::class); }
    public function subjects() { return \$this->hasMany(Subject::class); }
}
EOT,

    'Subject.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Subject extends Model {
    protected \$fillable = ['name', 'school_class_id', 'teacher_id'];
    public function schoolClass() { return \$this->belongsTo(SchoolClass::class); }
    public function teacher() { return \$this->belongsTo(Teacher::class); }
}
EOT,

    'Student.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Student extends Model {
    protected \$fillable = ['user_id', 'student_id', 'stage_id', 'school_class_id', 'date_of_birth', 'gender', 'parent_phone', 'address'];
    public function user() { return \$this->belongsTo(User::class); }
    public function stage() { return \$this->belongsTo(Stage::class); }
    public function schoolClass() { return \$this->belongsTo(SchoolClass::class); }
    public function attendances() { return \$this->hasMany(Attendance::class); }
}
EOT,

    'Attendance.php' => <<<EOT
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Attendance extends Model {
    protected \$fillable = ['student_id', 'date', 'status'];
    public function student() { return \$this->belongsTo(Student::class); }
}
EOT,
];

foreach ($models as $name => $content) {
    file_put_contents($modelsPath . $name, $content);
}
echo "Models generated successfully.\n";

