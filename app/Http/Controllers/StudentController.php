<?php
namespace App\Http\Controllers;
use App\Models\Student;
use App\Models\User;
use App\Models\Role;
use App\Models\Stage;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller {
    public function index() { $students = Student::with(['user', 'stage', 'schoolClass'])->latest()->get(); return view('students.index', compact('students')); }
    public function create() { 
        $stages = Stage::all(); 
        $classes = SchoolClass::all(); 
        return view('students.create', compact('stages', 'classes')); 
    }
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'student_id' => 'required|string|unique:students',
            'stage_id' => 'required|exists:stages,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:Male,Female',
        ]);

        DB::transaction(function () use ($request) {
            $role = Role::firstOrCreate(['name' => 'Student']);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role_id' => $role->id
            ]);
            Student::create([
                'user_id' => $user->id,
                'student_id' => $request->student_id,
                'stage_id' => $request->stage_id,
                'school_class_id' => $request->school_class_id,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'parent_phone' => $request->parent_phone,
                'address' => $request->address
            ]);
        });

        return redirect()->route('students.index')->with('success', 'تمت إضافة الطالب بنجاح');
    }
    public function edit(Student $student) { 
        $stages = Stage::all(); 
        $classes = SchoolClass::all(); 
        return view('students.edit', compact('student', 'stages', 'classes')); 
    }
    public function update(Request $request, Student $student) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->user_id,
            'password' => 'nullable|string|min:8',
            'student_id' => 'required|string|unique:students,student_id,' . $student->id,
            'stage_id' => 'required|exists:stages,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:Male,Female',
        ]);

        DB::transaction(function () use ($request, $student) {
            $user = $student->user;
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = $request->password;
            }
            $user->save();
            
            $student->update([
                'student_id' => $request->student_id,
                'stage_id' => $request->stage_id,
                'school_class_id' => $request->school_class_id,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'parent_phone' => $request->parent_phone,
                'address' => $request->address
            ]);
        });

        return redirect()->route('students.index')->with('success', 'تم تحديث بيانات الطالب بنجاح');
    }
    public function destroy(Student $student) {
        DB::transaction(function () use ($student) {
            $userId = $student->user_id;
            $student->delete();
            User::where('id', $userId)->delete();
        });
        return redirect()->route('students.index')->with('success', 'تم حذف الطالب بنجاح');
    }
}