<?php
$controllersPath = __DIR__ . '/app/Http/Controllers/';
$viewsPath = __DIR__ . '/resources/views/';

// Create view directories
@mkdir($viewsPath . 'teachers', 0777, true);
@mkdir($viewsPath . 'students', 0777, true);

// TEACHERS CONTROLLER
$teacherController = <<<EOT
<?php
namespace App\Http\Controllers;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller {
    public function index() { \$teachers = Teacher::with('user')->latest()->get(); return view('teachers.index', compact('teachers')); }
    public function create() { return view('teachers.create'); }
    public function store(Request \$request) {
        \$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        DB::transaction(function () use (\$request) {
            \$role = Role::firstOrCreate(['name' => 'Teacher']);
            \$user = User::create([
                'name' => \$request->name,
                'email' => \$request->email,
                'password' => Hash::make(\$request->password),
                'role_id' => \$role->id
            ]);
            Teacher::create([
                'user_id' => \$user->id,
                'phone_number' => \$request->phone_number,
                'address' => \$request->address
            ]);
        });

        return redirect()->route('teachers.index')->with('success', 'تمت إضافة المعلم بنجاح');
    }
    public function edit(Teacher \$teacher) { return view('teachers.edit', compact('teacher')); }
    public function update(Request \$request, Teacher \$teacher) {
        \$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . \$teacher->user_id,
            'password' => 'nullable|string|min:8',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        DB::transaction(function () use (\$request, \$teacher) {
            \$user = \$teacher->user;
            \$user->name = \$request->name;
            \$user->email = \$request->email;
            if (\$request->filled('password')) {
                \$user->password = Hash::make(\$request->password);
            }
            \$user->save();
            
            \$teacher->update([
                'phone_number' => \$request->phone_number,
                'address' => \$request->address
            ]);
        });

        return redirect()->route('teachers.index')->with('success', 'تم تحديث بيانات المعلم بنجاح');
    }
    public function destroy(Teacher \$teacher) {
        DB::transaction(function () use (\$teacher) {
            \$userId = \$teacher->user_id;
            \$teacher->delete();
            User::where('id', \$userId)->delete();
        });
        return redirect()->route('teachers.index')->with('success', 'تم حذف المعلم بنجاح');
    }
}
EOT;

file_put_contents($controllersPath . 'TeacherController.php', $teacherController);

// TEACHERS VIEWS
$teacherIndex = <<<EOT
@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-700">إدارة المعلمين</h2>
    <a href="{{ route('teachers.create') }}" class="bg-[#7CB342] hover:bg-[#689f38] text-white px-4 py-2 rounded text-sm font-semibold transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> إضافة معلم
    </a>
</div>
<div class="bg-white rounded shadow-sm overflow-hidden">
    <table class="w-full text-sm text-center">
        <thead class="bg-primary text-white font-bold">
            <tr><th class="py-3 px-4 font-semibold">#</th><th class="py-3 px-4 font-semibold">اسم المعلم</th><th class="py-3 px-4 font-semibold">البريد الإلكتروني</th><th class="py-3 px-4 font-semibold">رقم الهاتف</th><th class="py-3 px-4 font-semibold">العمليات</th></tr>
        </thead>
        <tbody>
            @forelse(\$teachers as \$teacher)
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-3 px-4 text-gray-500">{{ \$loop->iteration }}</td>
                <td class="py-3 px-4 text-gray-700 font-bold">{{ \$teacher->user->name ?? '-' }}</td>
                <td class="py-3 px-4 text-gray-500">{{ \$teacher->user->email ?? '-' }}</td>
                <td class="py-3 px-4 text-gray-500">{{ \$teacher->phone_number ?? '-' }}</td>
                <td class="py-3 px-4 flex justify-center gap-2">
                    <a href="{{ route('teachers.edit', \$teacher) }}" class="bg-blue-100 text-blue-600 px-3 py-1 rounded hover:bg-blue-200 transition"><i class="fa-solid fa-pen"></i></a>
                    <form action="{{ route('teachers.destroy', \$teacher) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟ سيتم حذف حساب المستخدم أيضاً!')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200 transition"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="py-6 text-gray-500">لا يوجد معلمين</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
EOT;

$teacherCreate = <<<EOT
@extends('layouts.app')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-3xl mx-auto mt-10">
    <h2 class="text-xl font-bold text-gray-700 mb-6 border-b pb-3">إضافة معلم جديد</h2>
    <form action="{{ route('teachers.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">اسم المعلم</label>
                <input type="text" name="name" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">البريد الإلكتروني</label>
                <input type="email" name="email" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">كلمة المرور (للدخول للنظام)</label>
                <input type="password" name="password" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">رقم الهاتف</label>
                <input type="text" name="phone_number" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]">
            </div>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-600 mb-2">العنوان</label>
            <input type="text" name="address" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]">
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('teachers.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded hover:bg-gray-200 transition">إلغاء</a>
            <button type="submit" class="bg-[#7CB342] text-white px-6 py-2 rounded hover:bg-[#689f38] transition font-bold">حفظ</button>
        </div>
    </form>
</div>
@endsection
EOT;

$teacherEdit = <<<EOT
@extends('layouts.app')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-3xl mx-auto mt-10">
    <h2 class="text-xl font-bold text-gray-700 mb-6 border-b pb-3">تعديل بيانات المعلم</h2>
    <form action="{{ route('teachers.update', \$teacher) }}" method="POST">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">اسم المعلم</label>
                <input type="text" name="name" value="{{ \$teacher->user->name ?? '' }}" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ \$teacher->user->email ?? '' }}" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">كلمة المرور (اتركها فارغة إذا لم ترد التغيير)</label>
                <input type="password" name="password" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">رقم الهاتف</label>
                <input type="text" name="phone_number" value="{{ \$teacher->phone_number }}" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]">
            </div>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-600 mb-2">العنوان</label>
            <input type="text" name="address" value="{{ \$teacher->address }}" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]">
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('teachers.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded hover:bg-gray-200 transition">إلغاء</a>
            <button type="submit" class="bg-[#7CB342] text-white px-6 py-2 rounded hover:bg-[#689f38] transition font-bold">تحديث</button>
        </div>
    </form>
</div>
@endsection
EOT;

file_put_contents($viewsPath . 'teachers/index.blade.php', $teacherIndex);
file_put_contents($viewsPath . 'teachers/create.blade.php', $teacherCreate);
file_put_contents($viewsPath . 'teachers/edit.blade.php', $teacherEdit);

// STUDENTS CONTROLLER
$studentController = <<<EOT
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
    public function index() { \$students = Student::with(['user', 'stage', 'schoolClass'])->latest()->get(); return view('students.index', compact('students')); }
    public function create() { 
        \$stages = Stage::all(); 
        \$classes = SchoolClass::all(); 
        return view('students.create', compact('stages', 'classes')); 
    }
    public function store(Request \$request) {
        \$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'student_id' => 'required|string|unique:students',
            'stage_id' => 'required|exists:stages,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:Male,Female',
        ]);

        DB::transaction(function () use (\$request) {
            \$role = Role::firstOrCreate(['name' => 'Student']);
            \$user = User::create([
                'name' => \$request->name,
                'email' => \$request->email,
                'password' => Hash::make(\$request->password),
                'role_id' => \$role->id
            ]);
            Student::create([
                'user_id' => \$user->id,
                'student_id' => \$request->student_id,
                'stage_id' => \$request->stage_id,
                'school_class_id' => \$request->school_class_id,
                'date_of_birth' => \$request->date_of_birth,
                'gender' => \$request->gender,
                'parent_phone' => \$request->parent_phone,
                'address' => \$request->address
            ]);
        });

        return redirect()->route('students.index')->with('success', 'تمت إضافة الطالب بنجاح');
    }
    public function edit(Student \$student) { 
        \$stages = Stage::all(); 
        \$classes = SchoolClass::all(); 
        return view('students.edit', compact('student', 'stages', 'classes')); 
    }
    public function update(Request \$request, Student \$student) {
        \$request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . \$student->user_id,
            'password' => 'nullable|string|min:8',
            'student_id' => 'required|string|unique:students,student_id,' . \$student->id,
            'stage_id' => 'required|exists:stages,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:Male,Female',
        ]);

        DB::transaction(function () use (\$request, \$student) {
            \$user = \$student->user;
            \$user->name = \$request->name;
            \$user->email = \$request->email;
            if (\$request->filled('password')) {
                \$user->password = Hash::make(\$request->password);
            }
            \$user->save();
            
            \$student->update([
                'student_id' => \$request->student_id,
                'stage_id' => \$request->stage_id,
                'school_class_id' => \$request->school_class_id,
                'date_of_birth' => \$request->date_of_birth,
                'gender' => \$request->gender,
                'parent_phone' => \$request->parent_phone,
                'address' => \$request->address
            ]);
        });

        return redirect()->route('students.index')->with('success', 'تم تحديث بيانات الطالب بنجاح');
    }
    public function destroy(Student \$student) {
        DB::transaction(function () use (\$student) {
            \$userId = \$student->user_id;
            \$student->delete();
            User::where('id', \$userId)->delete();
        });
        return redirect()->route('students.index')->with('success', 'تم حذف الطالب بنجاح');
    }
}
EOT;

file_put_contents($controllersPath . 'StudentController.php', $studentController);

// STUDENTS VIEWS
$studentIndex = <<<EOT
@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-700">إدارة الطلاب</h2>
    <a href="{{ route('students.create') }}" class="bg-[#7CB342] hover:bg-[#689f38] text-white px-4 py-2 rounded text-sm font-semibold transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> إضافة طالب
    </a>
</div>
<div class="bg-white rounded shadow-sm overflow-hidden">
    <table class="w-full text-sm text-center">
        <thead class="bg-primary text-white font-bold">
            <tr><th class="py-3 px-4 font-semibold">رقم الطالب</th><th class="py-3 px-4 font-semibold">الاسم</th><th class="py-3 px-4 font-semibold">المرحلة</th><th class="py-3 px-4 font-semibold">الصف</th><th class="py-3 px-4 font-semibold">الجنس</th><th class="py-3 px-4 font-semibold">العمليات</th></tr>
        </thead>
        <tbody>
            @forelse(\$students as \$student)
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-3 px-4 text-gray-500 font-bold" dir="ltr">{{ \$student->student_id }}</td>
                <td class="py-3 px-4 text-gray-700 font-bold">{{ \$student->user->name ?? '-' }}</td>
                <td class="py-3 px-4 text-green-600">{{ \$student->stage->name ?? '-' }}</td>
                <td class="py-3 px-4 text-blue-600">{{ \$student->schoolClass->name ?? '-' }}</td>
                <td class="py-3 px-4 text-gray-500">{{ \$student->gender == 'Male' ? 'ذكر' : 'أنثى' }}</td>
                <td class="py-3 px-4 flex justify-center gap-2">
                    <a href="{{ route('students.edit', \$student) }}" class="bg-blue-100 text-blue-600 px-3 py-1 rounded hover:bg-blue-200 transition"><i class="fa-solid fa-pen"></i></a>
                    <form action="{{ route('students.destroy', \$student) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟ سيتم حذف حساب المستخدم أيضاً!')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200 transition"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="py-6 text-gray-500">لا يوجد طلاب مسجلين</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
EOT;

$studentCreate = <<<EOT
@extends('layouts.app')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-4xl mx-auto mt-6">
    <h2 class="text-xl font-bold text-gray-700 mb-6 border-b pb-3">إضافة طالب جديد</h2>
    <form action="{{ route('students.store') }}" method="POST">
        @csrf
        
        <h3 class="text-md font-bold text-gray-600 mb-3"><i class="fa-solid fa-user text-[#7CB342]"></i> بيانات الحساب</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-gray-50 p-4 rounded border border-gray-100">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">اسم الطالب (الكامل)</label>
                <input type="text" name="name" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">البريد الإلكتروني</label>
                <input type="email" name="email" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">كلمة المرور</label>
                <input type="password" name="password" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
            </div>
        </div>

        <h3 class="text-md font-bold text-gray-600 mb-3"><i class="fa-solid fa-graduation-cap text-[#7CB342]"></i> البيانات الأكاديمية</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-gray-50 p-4 rounded border border-gray-100">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">رقم الطالب الأكاديمي (ID)</label>
                <input type="text" name="student_id" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">المرحلة الدراسية</label>
                <select name="stage_id" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
                    <option value="">-- اختر المرحلة --</option>
                    @foreach(\$stages as \$stage)
                        <option value="{{ \$stage->id }}">{{ \$stage->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">الصف الدراسي</label>
                <select name="school_class_id" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
                    <option value="">-- اختر الصف --</option>
                    @foreach(\$classes as \$class)
                        <option value="{{ \$class->id }}">{{ \$class->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <h3 class="text-md font-bold text-gray-600 mb-3"><i class="fa-solid fa-id-card text-[#7CB342]"></i> بيانات إضافية</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 bg-gray-50 p-4 rounded border border-gray-100">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">تاريخ الميلاد</label>
                <input type="date" name="date_of_birth" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">الجنس</label>
                <select name="gender" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
                    <option value="Male">ذكر</option>
                    <option value="Female">أنثى</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">رقم هاتف ولي الأمر</label>
                <input type="text" name="parent_phone" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">العنوان</label>
                <input type="text" name="address" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]">
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-4">
            <a href="{{ route('students.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded hover:bg-gray-200 transition">إلغاء</a>
            <button type="submit" class="bg-[#7CB342] text-white px-8 py-2 rounded hover:bg-[#689f38] transition font-bold">تسجيل الطالب</button>
        </div>
    </form>
</div>
@endsection
EOT;

$studentEdit = <<<EOT
@extends('layouts.app')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-4xl mx-auto mt-6">
    <h2 class="text-xl font-bold text-gray-700 mb-6 border-b pb-3">تعديل بيانات الطالب</h2>
    <form action="{{ route('students.update', \$student) }}" method="POST">
        @csrf @method('PUT')
        
        <h3 class="text-md font-bold text-gray-600 mb-3"><i class="fa-solid fa-user text-[#7CB342]"></i> بيانات الحساب</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-gray-50 p-4 rounded border border-gray-100">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">اسم الطالب (الكامل)</label>
                <input type="text" name="name" value="{{ \$student->user->name ?? '' }}" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ \$student->user->email ?? '' }}" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">كلمة المرور (اتركها فارغة للتخطي)</label>
                <input type="password" name="password" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]">
            </div>
        </div>

        <h3 class="text-md font-bold text-gray-600 mb-3"><i class="fa-solid fa-graduation-cap text-[#7CB342]"></i> البيانات الأكاديمية</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-gray-50 p-4 rounded border border-gray-100">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">رقم الطالب الأكاديمي (ID)</label>
                <input type="text" name="student_id" value="{{ \$student->student_id }}" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">المرحلة الدراسية</label>
                <select name="stage_id" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
                    @foreach(\$stages as \$stage)
                        <option value="{{ \$stage->id }}" {{ \$student->stage_id == \$stage->id ? 'selected' : '' }}>{{ \$stage->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">الصف الدراسي</label>
                <select name="school_class_id" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
                    @foreach(\$classes as \$class)
                        <option value="{{ \$class->id }}" {{ \$student->school_class_id == \$class->id ? 'selected' : '' }}>{{ \$class->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <h3 class="text-md font-bold text-gray-600 mb-3"><i class="fa-solid fa-id-card text-[#7CB342]"></i> بيانات إضافية</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 bg-gray-50 p-4 rounded border border-gray-100">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">تاريخ الميلاد</label>
                <input type="date" name="date_of_birth" value="{{ \$student->date_of_birth }}" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">الجنس</label>
                <select name="gender" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
                    <option value="Male" {{ \$student->gender == 'Male' ? 'selected' : '' }}>ذكر</option>
                    <option value="Female" {{ \$student->gender == 'Female' ? 'selected' : '' }}>أنثى</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">رقم هاتف ولي الأمر</label>
                <input type="text" name="parent_phone" value="{{ \$student->parent_phone }}" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">العنوان</label>
                <input type="text" name="address" value="{{ \$student->address }}" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]">
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-4">
            <a href="{{ route('students.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded hover:bg-gray-200 transition">إلغاء</a>
            <button type="submit" class="bg-[#7CB342] text-white px-8 py-2 rounded hover:bg-[#689f38] transition font-bold">تحديث البيانات</button>
        </div>
    </form>
</div>
@endsection
EOT;

file_put_contents($viewsPath . 'students/index.blade.php', $studentIndex);
file_put_contents($viewsPath . 'students/create.blade.php', $studentCreate);
file_put_contents($viewsPath . 'students/edit.blade.php', $studentEdit);

echo "Teachers and Students CRUD generated.\n";
