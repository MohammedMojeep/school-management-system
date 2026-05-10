<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;
use App\Models\Stage;
use App\Models\SchoolClass;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Subject;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['Admin', 'Teacher', 'Student'];
        foreach ($roles as $r) {
            Role::create(['name' => $r]);
        }

        $adminRole = Role::where('name', 'Admin')->first();
        $teacherRole = Role::where('name', 'Teacher')->first();
        $studentRole = Role::where('name', 'Student')->first();

        // Admin
        User::create([
            'name' => 'مدير النظام',
            'email' => 'm@school.com',
            'password' => '3',
            'role_id' => $adminRole->id
        ]);

        // Teacher User
        $teacherUser = User::create([
            'name' => 'أحمد محمد',
            'email' => 't@school.com',
            'password' => '1',
            'role_id' => $teacherRole->id
        ]);

        // Student User
        $studentUser = User::create([
            'name' => 'خالد علي',
            'email' => 'p@school.com',
            'password' => '2',
            'role_id' => $studentRole->id
        ]);

        // Stages
        $stage1 = Stage::create(['name' => 'المرحلة الابتدائية']);
        $stage2 = Stage::create(['name' => 'المرحلة المتوسطة']);

        // Classes
        $class1 = SchoolClass::create(['name' => 'الصف الأول الابتدائي', 'stage_id' => $stage1->id]);
        $class2 = SchoolClass::create(['name' => 'الصف الأول المتوسط', 'stage_id' => $stage2->id]);

        // Teacher
        $teacher = Teacher::create([
            'user_id' => $teacherUser->id,
            'phone_number' => '0500000000',
            'address' => 'الرياض، المملكة العربية السعودية'
        ]);

        // Subject
        Subject::create([
            'name' => 'الرياضيات',
            'school_class_id' => $class1->id,
            'teacher_id' => $teacher->id
        ]);

        // Student
        Student::create([
            'user_id' => $studentUser->id,
            'student_id' => 'STU-1001',
            'stage_id' => $stage1->id,
            'school_class_id' => $class1->id,
            'date_of_birth' => '2015-05-10',
            'gender' => 'Male',
            'parent_phone' => '0555555555',
            'address' => 'الرياض'
        ]);
    }
}
