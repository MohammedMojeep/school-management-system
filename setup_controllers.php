<?php

$controllersPath = __DIR__ . '/app/Http/Controllers/';

$controllers = [
    'StudentController.php' => <<<EOT
<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Grade;
use App\Models\Section;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(Request \$request)
    {
        \$query = Student::with(['user', 'grade', 'section']);
        
        if (\$request->has('search')) {
            \$search = \$request->search;
            \$query->whereHas('user', function(\$q) use (\$search) {
                \$q->where('name', 'like', "%{\$search}%");
            })->orWhere('student_id', 'like', "%{\$search}%");
        }

        \$students = \$query->paginate(10);
        return view('students.index', compact('students'));
    }

    public function create()
    {
        \$grades = Grade::all();
        \$sections = Section::all();
        return view('students.create', compact('grades', 'sections'));
    }

    public function store(Request \$request)
    {
        \$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'student_id' => 'required|unique:students,student_id',
            'grade_id' => 'required|exists:grades,id',
            'section_id' => 'required|exists:sections,id',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'parent_phone' => 'required',
            'address' => 'required'
        ]);

        \$studentRole = Role::where('name', 'Student')->first();

        \$user = User::create([
            'name' => \$request->name,
            'email' => \$request->email,
            'password' => Hash::make(\$request->password),
            'role_id' => \$studentRole->id
        ]);

        Student::create([
            'user_id' => \$user->id,
            'student_id' => \$request->student_id,
            'grade_id' => \$request->grade_id,
            'section_id' => \$request->section_id,
            'date_of_birth' => \$request->date_of_birth,
            'gender' => \$request->gender,
            'parent_phone' => \$request->parent_phone,
            'address' => \$request->address
        ]);

        return redirect()->route('students.index')->with('success', 'تمت إضافة الطالب بنجاح');
    }

    public function edit(Student \$student)
    {
        \$grades = Grade::all();
        \$sections = Section::all();
        return view('students.edit', compact('student', 'grades', 'sections'));
    }

    public function update(Request \$request, Student \$student)
    {
        \$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . \$student->user_id,
            'grade_id' => 'required|exists:grades,id',
            'section_id' => 'required|exists:sections,id',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'parent_phone' => 'required',
            'address' => 'required'
        ]);

        \$student->user->update([
            'name' => \$request->name,
            'email' => \$request->email
        ]);
        
        if(\$request->password) {
            \$student->user->update(['password' => Hash::make(\$request->password)]);
        }

        \$student->update([
            'grade_id' => \$request->grade_id,
            'section_id' => \$request->section_id,
            'date_of_birth' => \$request->date_of_birth,
            'gender' => \$request->gender,
            'parent_phone' => \$request->parent_phone,
            'address' => \$request->address
        ]);

        return redirect()->route('students.index')->with('success', 'تم تحديث بيانات الطالب بنجاح');
    }

    public function destroy(Student \$student)
    {
        \$student->user->delete(); // This will cascade delete student due to DB constraints
        return redirect()->route('students.index')->with('success', 'تم حذف الطالب بنجاح');
    }
}
EOT,

    'TeacherController.php' => <<<EOT
<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index(Request \$request)
    {
        \$query = Teacher::with('user');
        
        if (\$request->has('search')) {
            \$search = \$request->search;
            \$query->whereHas('user', function(\$q) use (\$search) {
                \$q->where('name', 'like', "%{\$search}%");
            });
        }

        \$teachers = \$query->paginate(10);
        return view('teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('teachers.create');
    }

    public function store(Request \$request)
    {
        \$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'subject' => 'required|string',
            'salary' => 'required|numeric',
            'phone_number' => 'required',
            'address' => 'required'
        ]);

        \$teacherRole = Role::where('name', 'Teacher')->first();

        \$user = User::create([
            'name' => \$request->name,
            'email' => \$request->email,
            'password' => Hash::make(\$request->password),
            'role_id' => \$teacherRole->id
        ]);

        Teacher::create([
            'user_id' => \$user->id,
            'subject' => \$request->subject,
            'salary' => \$request->salary,
            'phone_number' => \$request->phone_number,
            'address' => \$request->address
        ]);

        return redirect()->route('teachers.index')->with('success', 'تمت إضافة المعلم بنجاح');
    }

    public function edit(Teacher \$teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request \$request, Teacher \$teacher)
    {
        \$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . \$teacher->user_id,
            'subject' => 'required|string',
            'salary' => 'required|numeric',
            'phone_number' => 'required',
            'address' => 'required'
        ]);

        \$teacher->user->update([
            'name' => \$request->name,
            'email' => \$request->email
        ]);
        
        if(\$request->password) {
            \$teacher->user->update(['password' => Hash::make(\$request->password)]);
        }

        \$teacher->update([
            'subject' => \$request->subject,
            'salary' => \$request->salary,
            'phone_number' => \$request->phone_number,
            'address' => \$request->address
        ]);

        return redirect()->route('teachers.index')->with('success', 'تم تحديث بيانات المعلم بنجاح');
    }

    public function destroy(Teacher \$teacher)
    {
        \$teacher->user->delete();
        return redirect()->route('teachers.index')->with('success', 'تم حذف المعلم بنجاح');
    }
}
EOT,
];

foreach ($controllers as $name => $content) {
    file_put_contents($controllersPath . $name, $content);
}
echo "Controllers generated successfully.\n";
