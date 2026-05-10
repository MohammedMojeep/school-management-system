<?php
$controllersPath = __DIR__ . '/app/Http/Controllers/';
$viewsPath = __DIR__ . '/resources/views/';

// Create view directories
@mkdir($viewsPath . 'subjects', 0777, true);
@mkdir($viewsPath . 'attendances', 0777, true);

// SUBJECTS CONTROLLER
$subjectController = <<<EOT
<?php
namespace App\Http\Controllers;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SubjectController extends Controller {
    public function index() { \$subjects = Subject::with(['schoolClass', 'teacher.user'])->latest()->get(); return view('subjects.index', compact('subjects')); }
    public function create() { \$classes = SchoolClass::all(); \$teachers = Teacher::with('user')->get(); return view('subjects.create', compact('classes', 'teachers')); }
    public function store(Request \$request) {
        \$request->validate(['name' => 'required|string|max:255', 'school_class_id' => 'required|exists:school_classes,id', 'teacher_id' => 'required|exists:teachers,id']);
        Subject::create(\$request->all());
        return redirect()->route('subjects.index')->with('success', 'تمت إضافة المادة بنجاح');
    }
    public function edit(Subject \$subject) { \$classes = SchoolClass::all(); \$teachers = Teacher::with('user')->get(); return view('subjects.edit', compact('subject', 'classes', 'teachers')); }
    public function update(Request \$request, Subject \$subject) {
        \$request->validate(['name' => 'required|string|max:255', 'school_class_id' => 'required|exists:school_classes,id', 'teacher_id' => 'required|exists:teachers,id']);
        \$subject->update(\$request->all());
        return redirect()->route('subjects.index')->with('success', 'تم تحديث المادة بنجاح');
    }
    public function destroy(Subject \$subject) {
        \$subject->delete();
        return redirect()->route('subjects.index')->with('success', 'تم حذف المادة بنجاح');
    }
}
EOT;

file_put_contents($controllersPath . 'SubjectController.php', $subjectController);

// SUBJECTS VIEWS
$subjectIndex = <<<EOT
@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-700">إدارة المواد الدراسية</h2>
    <a href="{{ route('subjects.create') }}" class="bg-[#7CB342] hover:bg-[#689f38] text-white px-4 py-2 rounded text-sm font-semibold transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> إضافة مادة
    </a>
</div>
<div class="bg-white rounded shadow-sm overflow-hidden">
    <table class="w-full text-sm text-center">
        <thead class="bg-primary text-white font-bold">
            <tr><th class="py-3 px-4 font-semibold">#</th><th class="py-3 px-4 font-semibold">اسم المادة</th><th class="py-3 px-4 font-semibold">الصف</th><th class="py-3 px-4 font-semibold">المعلم</th><th class="py-3 px-4 font-semibold">العمليات</th></tr>
        </thead>
        <tbody>
            @forelse(\$subjects as \$subj)
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-3 px-4 text-gray-500">{{ \$loop->iteration }}</td>
                <td class="py-3 px-4 text-gray-700 font-bold">{{ \$subj->name }}</td>
                <td class="py-3 px-4 text-green-600">{{ \$subj->schoolClass->name ?? '-' }}</td>
                <td class="py-3 px-4 text-gray-700">{{ \$subj->teacher->user->name ?? '-' }}</td>
                <td class="py-3 px-4 flex justify-center gap-2">
                    <a href="{{ route('subjects.edit', \$subj) }}" class="bg-blue-100 text-blue-600 px-3 py-1 rounded hover:bg-blue-200 transition"><i class="fa-solid fa-pen"></i></a>
                    <form action="{{ route('subjects.destroy', \$subj) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200 transition"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="py-6 text-gray-500">لا توجد مواد دراسية</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
EOT;

$subjectCreate = <<<EOT
@extends('layouts.app')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-2xl mx-auto mt-10">
    <h2 class="text-xl font-bold text-gray-700 mb-6 border-b pb-3">إضافة مادة جديدة</h2>
    <form action="{{ route('subjects.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-600 mb-2">اسم المادة</label>
            <input type="text" name="name" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-600 mb-2">الصف الدراسي</label>
            <select name="school_class_id" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
                <option value="">-- اختر الصف --</option>
                @foreach(\$classes as \$class)
                    <option value="{{ \$class->id }}">{{ \$class->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-600 mb-2">المعلم</label>
            <select name="teacher_id" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
                <option value="">-- اختر المعلم --</option>
                @foreach(\$teachers as \$teacher)
                    <option value="{{ \$teacher->id }}">{{ \$teacher->user->name ?? 'معلم' }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('subjects.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded hover:bg-gray-200 transition">إلغاء</a>
            <button type="submit" class="bg-[#7CB342] text-white px-6 py-2 rounded hover:bg-[#689f38] transition font-bold">حفظ</button>
        </div>
    </form>
</div>
@endsection
EOT;

$subjectEdit = <<<EOT
@extends('layouts.app')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-2xl mx-auto mt-10">
    <h2 class="text-xl font-bold text-gray-700 mb-6 border-b pb-3">تعديل مادة: {{ \$subject->name }}</h2>
    <form action="{{ route('subjects.update', \$subject) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-600 mb-2">اسم المادة</label>
            <input type="text" name="name" value="{{ \$subject->name }}" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-600 mb-2">الصف الدراسي</label>
            <select name="school_class_id" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
                @foreach(\$classes as \$class)
                    <option value="{{ \$class->id }}" {{ \$subject->school_class_id == \$class->id ? 'selected' : '' }}>{{ \$class->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-600 mb-2">المعلم</label>
            <select name="teacher_id" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
                @foreach(\$teachers as \$teacher)
                    <option value="{{ \$teacher->id }}" {{ \$subject->teacher_id == \$teacher->id ? 'selected' : '' }}>{{ \$teacher->user->name ?? 'معلم' }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('subjects.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded hover:bg-gray-200 transition">إلغاء</a>
            <button type="submit" class="bg-[#7CB342] text-white px-6 py-2 rounded hover:bg-[#689f38] transition font-bold">تحديث</button>
        </div>
    </form>
</div>
@endsection
EOT;

file_put_contents($viewsPath . 'subjects/index.blade.php', $subjectIndex);
file_put_contents($viewsPath . 'subjects/create.blade.php', $subjectCreate);
file_put_contents($viewsPath . 'subjects/edit.blade.php', $subjectEdit);


// ATTENDANCES CONTROLLER
$attendanceController = <<<EOT
<?php
namespace App\Http\Controllers;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller {
    public function index(Request \$request) { 
        \$date = \$request->date ?? date('Y-m-d');
        \$attendances = Attendance::with('student.user')->where('date', \$date)->get(); 
        return view('attendances.index', compact('attendances', 'date')); 
    }
    public function create() { \$students = Student::with('user')->get(); return view('attendances.create', compact('students')); }
    public function store(Request \$request) {
        \$request->validate(['student_id' => 'required|exists:students,id', 'date' => 'required|date', 'status' => 'required|in:Present,Absent,Late']);
        
        Attendance::updateOrCreate(
            ['student_id' => \$request->student_id, 'date' => \$request->date],
            ['status' => \$request->status]
        );
        
        return redirect()->route('attendances.index', ['date' => \$request->date])->with('success', 'تم حفظ الحضور بنجاح');
    }
    // Simple direct delete to save time
    public function destroy(Attendance \$attendance) {
        \$attendance->delete();
        return redirect()->back()->with('success', 'تم الحذف بنجاح');
    }
}
EOT;

file_put_contents($controllersPath . 'AttendanceController.php', $attendanceController);

// ATTENDANCES VIEWS
$attendanceIndex = <<<EOT
@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-700">سجل الحضور والغياب</h2>
    <a href="{{ route('attendances.create') }}" class="bg-[#7CB342] hover:bg-[#689f38] text-white px-4 py-2 rounded text-sm font-semibold transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> تسجيل حضور جديد
    </a>
</div>

<div class="bg-white rounded shadow-sm p-4 mb-6">
    <form action="{{ route('attendances.index') }}" method="GET" class="flex gap-4 items-end">
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">اختر التاريخ</label>
            <input type="date" name="date" value="{{ \$date }}" class="border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]">
        </div>
        <button type="submit" class="bg-primary text-white px-6 py-2 rounded hover:bg-opacity-90 transition font-bold">عرض</button>
    </form>
</div>

<div class="bg-white rounded shadow-sm overflow-hidden">
    <table class="w-full text-sm text-center">
        <thead class="bg-primary text-white font-bold">
            <tr><th class="py-3 px-4 font-semibold">#</th><th class="py-3 px-4 font-semibold">اسم الطالب</th><th class="py-3 px-4 font-semibold">التاريخ</th><th class="py-3 px-4 font-semibold">الحالة</th><th class="py-3 px-4 font-semibold">إلغاء</th></tr>
        </thead>
        <tbody>
            @forelse(\$attendances as \$att)
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-3 px-4 text-gray-500">{{ \$loop->iteration }}</td>
                <td class="py-3 px-4 text-gray-700 font-bold">{{ \$att->student->user->name ?? '-' }}</td>
                <td class="py-3 px-4 text-gray-500">{{ \$att->date }}</td>
                <td class="py-3 px-4 font-bold 
                    {{ \$att->status == 'Present' ? 'text-green-600' : (\$att->status == 'Absent' ? 'text-red-600' : 'text-yellow-600') }}">
                    {{ \$att->status == 'Present' ? 'حاضر' : (\$att->status == 'Absent' ? 'غائب' : 'متأخر') }}
                </td>
                <td class="py-3 px-4 flex justify-center gap-2">
                    <form action="{{ route('attendances.destroy', \$att) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200 transition"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="py-6 text-gray-500">لا يوجد سجلات حضور لهذا اليوم</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
EOT;

$attendanceCreate = <<<EOT
@extends('layouts.app')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-2xl mx-auto mt-10">
    <h2 class="text-xl font-bold text-gray-700 mb-6 border-b pb-3">تسجيل حضور طالب</h2>
    <form action="{{ route('attendances.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-600 mb-2">الطالب</label>
            <select name="student_id" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
                <option value="">-- اختر الطالب --</option>
                @foreach(\$students as \$student)
                    <option value="{{ \$student->id }}">{{ \$student->user->name ?? 'طالب' }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-600 mb-2">التاريخ</label>
            <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-600 mb-2">الحالة</label>
            <select name="status" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
                <option value="Present">حاضر</option>
                <option value="Absent">غائب</option>
                <option value="Late">متأخر</option>
            </select>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('attendances.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded hover:bg-gray-200 transition">إلغاء</a>
            <button type="submit" class="bg-[#7CB342] text-white px-6 py-2 rounded hover:bg-[#689f38] transition font-bold">تسجيل</button>
        </div>
    </form>
</div>
@endsection
EOT;

file_put_contents($viewsPath . 'attendances/index.blade.php', $attendanceIndex);
file_put_contents($viewsPath . 'attendances/create.blade.php', $attendanceCreate);

echo "Subjects and Attendances CRUD generated.\n";
