<?php
$controllersPath = __DIR__ . '/app/Http/Controllers/';
$viewsPath = __DIR__ . '/resources/views/';

// Create view directories
@mkdir($viewsPath . 'stages', 0777, true);
@mkdir($viewsPath . 'school_classes', 0777, true);

// STAGES CONTROLLER
$stageController = <<<EOT
<?php
namespace App\Http\Controllers;
use App\Models\Stage;
use Illuminate\Http\Request;

class StageController extends Controller {
    public function index() { \$stages = Stage::latest()->get(); return view('stages.index', compact('stages')); }
    public function create() { return view('stages.create'); }
    public function store(Request \$request) {
        \$request->validate(['name' => 'required|string|max:255', 'notes' => 'nullable|string']);
        Stage::create(\$request->all());
        return redirect()->route('stages.index')->with('success', 'تمت إضافة المرحلة بنجاح');
    }
    public function edit(Stage \$stage) { return view('stages.edit', compact('stage')); }
    public function update(Request \$request, Stage \$stage) {
        \$request->validate(['name' => 'required|string|max:255', 'notes' => 'nullable|string']);
        \$stage->update(\$request->all());
        return redirect()->route('stages.index')->with('success', 'تم تحديث المرحلة بنجاح');
    }
    public function destroy(Stage \$stage) {
        \$stage->delete();
        return redirect()->route('stages.index')->with('success', 'تم حذف المرحلة بنجاح');
    }
}
EOT;

file_put_contents($controllersPath . 'StageController.php', $stageController);

// STAGES VIEWS
$stageIndex = <<<EOT
@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-700">إدارة المراحل الدراسية</h2>
    <a href="{{ route('stages.create') }}" class="bg-[#7CB342] hover:bg-[#689f38] text-white px-4 py-2 rounded text-sm font-semibold transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> إضافة مرحلة
    </a>
</div>
<div class="bg-white rounded shadow-sm overflow-hidden">
    <table class="w-full text-sm text-center">
        <thead class="bg-primary text-white font-bold">
            <tr><th class="py-3 px-4 font-semibold">#</th><th class="py-3 px-4 font-semibold">اسم المرحلة</th><th class="py-3 px-4 font-semibold">ملاحظات</th><th class="py-3 px-4 font-semibold">العمليات</th></tr>
        </thead>
        <tbody>
            @forelse(\$stages as \$stage)
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-3 px-4 text-gray-500">{{ \$loop->iteration }}</td>
                <td class="py-3 px-4 text-gray-700 font-bold">{{ \$stage->name }}</td>
                <td class="py-3 px-4 text-gray-500">{{ \$stage->notes ?? '-' }}</td>
                <td class="py-3 px-4 flex justify-center gap-2">
                    <a href="{{ route('stages.edit', \$stage) }}" class="bg-blue-100 text-blue-600 px-3 py-1 rounded hover:bg-blue-200 transition"><i class="fa-solid fa-pen"></i></a>
                    <form action="{{ route('stages.destroy', \$stage) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200 transition"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="py-6 text-gray-500">لا توجد مراحل دراسية</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
EOT;

$stageCreate = <<<EOT
@extends('layouts.app')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-2xl mx-auto mt-10">
    <h2 class="text-xl font-bold text-gray-700 mb-6 border-b pb-3">إضافة مرحلة جديدة</h2>
    <form action="{{ route('stages.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-600 mb-2">اسم المرحلة</label>
            <input type="text" name="name" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-600 mb-2">ملاحظات</label>
            <textarea name="notes" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]"></textarea>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('stages.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded hover:bg-gray-200 transition">إلغاء</a>
            <button type="submit" class="bg-[#7CB342] text-white px-6 py-2 rounded hover:bg-[#689f38] transition font-bold">حفظ</button>
        </div>
    </form>
</div>
@endsection
EOT;

$stageEdit = <<<EOT
@extends('layouts.app')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-2xl mx-auto mt-10">
    <h2 class="text-xl font-bold text-gray-700 mb-6 border-b pb-3">تعديل مرحلة: {{ \$stage->name }}</h2>
    <form action="{{ route('stages.update', \$stage) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-600 mb-2">اسم المرحلة</label>
            <input type="text" name="name" value="{{ \$stage->name }}" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-600 mb-2">ملاحظات</label>
            <textarea name="notes" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]">{{ \$stage->notes }}</textarea>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('stages.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded hover:bg-gray-200 transition">إلغاء</a>
            <button type="submit" class="bg-[#7CB342] text-white px-6 py-2 rounded hover:bg-[#689f38] transition font-bold">تحديث</button>
        </div>
    </form>
</div>
@endsection
EOT;

file_put_contents($viewsPath . 'stages/index.blade.php', $stageIndex);
file_put_contents($viewsPath . 'stages/create.blade.php', $stageCreate);
file_put_contents($viewsPath . 'stages/edit.blade.php', $stageEdit);

// SCHOOL CLASSES CONTROLLER
$classController = <<<EOT
<?php
namespace App\Http\Controllers;
use App\Models\SchoolClass;
use App\Models\Stage;
use Illuminate\Http\Request;

class SchoolClassController extends Controller {
    public function index() { \$classes = SchoolClass::with('stage')->latest()->get(); return view('school_classes.index', compact('classes')); }
    public function create() { \$stages = Stage::all(); return view('school_classes.create', compact('stages')); }
    public function store(Request \$request) {
        \$request->validate(['name' => 'required|string|max:255', 'stage_id' => 'required|exists:stages,id']);
        SchoolClass::create(\$request->all());
        return redirect()->route('school_classes.index')->with('success', 'تمت إضافة الصف بنجاح');
    }
    public function edit(SchoolClass \$schoolClass) { \$stages = Stage::all(); return view('school_classes.edit', compact('schoolClass', 'stages')); }
    public function update(Request \$request, SchoolClass \$schoolClass) {
        \$request->validate(['name' => 'required|string|max:255', 'stage_id' => 'required|exists:stages,id']);
        \$schoolClass->update(\$request->all());
        return redirect()->route('school_classes.index')->with('success', 'تم تحديث الصف بنجاح');
    }
    public function destroy(SchoolClass \$schoolClass) {
        \$schoolClass->delete();
        return redirect()->route('school_classes.index')->with('success', 'تم حذف الصف بنجاح');
    }
}
EOT;

file_put_contents($controllersPath . 'SchoolClassController.php', $classController);

// SCHOOL CLASSES VIEWS
$classIndex = <<<EOT
@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-700">إدارة الصفوف</h2>
    <a href="{{ route('school_classes.create') }}" class="bg-[#7CB342] hover:bg-[#689f38] text-white px-4 py-2 rounded text-sm font-semibold transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> إضافة صف
    </a>
</div>
<div class="bg-white rounded shadow-sm overflow-hidden">
    <table class="w-full text-sm text-center">
        <thead class="bg-primary text-white font-bold">
            <tr><th class="py-3 px-4 font-semibold">#</th><th class="py-3 px-4 font-semibold">اسم الصف</th><th class="py-3 px-4 font-semibold">المرحلة</th><th class="py-3 px-4 font-semibold">العمليات</th></tr>
        </thead>
        <tbody>
            @forelse(\$classes as \$cls)
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-3 px-4 text-gray-500">{{ \$loop->iteration }}</td>
                <td class="py-3 px-4 text-gray-700 font-bold">{{ \$cls->name }}</td>
                <td class="py-3 px-4 text-green-600">{{ \$cls->stage->name ?? '-' }}</td>
                <td class="py-3 px-4 flex justify-center gap-2">
                    <a href="{{ route('school_classes.edit', \$cls) }}" class="bg-blue-100 text-blue-600 px-3 py-1 rounded hover:bg-blue-200 transition"><i class="fa-solid fa-pen"></i></a>
                    <form action="{{ route('school_classes.destroy', \$cls) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200 transition"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="py-6 text-gray-500">لا توجد صفوف دراسية</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
EOT;

$classCreate = <<<EOT
@extends('layouts.app')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-2xl mx-auto mt-10">
    <h2 class="text-xl font-bold text-gray-700 mb-6 border-b pb-3">إضافة صف جديد</h2>
    <form action="{{ route('school_classes.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-600 mb-2">اسم الصف</label>
            <input type="text" name="name" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-600 mb-2">المرحلة الدراسية</label>
            <select name="stage_id" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
                <option value="">-- اختر المرحلة --</option>
                @foreach(\$stages as \$stage)
                    <option value="{{ \$stage->id }}">{{ \$stage->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('school_classes.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded hover:bg-gray-200 transition">إلغاء</a>
            <button type="submit" class="bg-[#7CB342] text-white px-6 py-2 rounded hover:bg-[#689f38] transition font-bold">حفظ</button>
        </div>
    </form>
</div>
@endsection
EOT;

$classEdit = <<<EOT
@extends('layouts.app')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-2xl mx-auto mt-10">
    <h2 class="text-xl font-bold text-gray-700 mb-6 border-b pb-3">تعديل صف: {{ \$schoolClass->name }}</h2>
    <form action="{{ route('school_classes.update', \$schoolClass) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-600 mb-2">اسم الصف</label>
            <input type="text" name="name" value="{{ \$schoolClass->name }}" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-600 mb-2">المرحلة الدراسية</label>
            <select name="stage_id" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
                @foreach(\$stages as \$stage)
                    <option value="{{ \$stage->id }}" {{ \$schoolClass->stage_id == \$stage->id ? 'selected' : '' }}>{{ \$stage->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('school_classes.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded hover:bg-gray-200 transition">إلغاء</a>
            <button type="submit" class="bg-[#7CB342] text-white px-6 py-2 rounded hover:bg-[#689f38] transition font-bold">تحديث</button>
        </div>
    </form>
</div>
@endsection
EOT;

file_put_contents($viewsPath . 'school_classes/index.blade.php', $classIndex);
file_put_contents($viewsPath . 'school_classes/create.blade.php', $classCreate);
file_put_contents($viewsPath . 'school_classes/edit.blade.php', $classEdit);

echo "Stages and SchoolClasses CRUD generated.\n";
