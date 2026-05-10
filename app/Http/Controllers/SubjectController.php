<?php
namespace App\Http\Controllers;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SubjectController extends Controller {
    public function index() { $subjects = Subject::with(['schoolClass', 'teacher.user'])->latest()->get(); return view('subjects.index', compact('subjects')); }
    public function create() { $classes = SchoolClass::all(); $teachers = Teacher::with('user')->get(); return view('subjects.create', compact('classes', 'teachers')); }
    public function store(Request $request) {
        $request->validate(['name' => 'required|string|max:255', 'school_class_id' => 'required|exists:school_classes,id', 'teacher_id' => 'required|exists:teachers,id']);
        Subject::create($request->all());
        return redirect()->route('subjects.index')->with('success', 'تمت إضافة المادة بنجاح');
    }
    public function edit(Subject $subject) { $classes = SchoolClass::all(); $teachers = Teacher::with('user')->get(); return view('subjects.edit', compact('subject', 'classes', 'teachers')); }
    public function update(Request $request, Subject $subject) {
        $request->validate(['name' => 'required|string|max:255', 'school_class_id' => 'required|exists:school_classes,id', 'teacher_id' => 'required|exists:teachers,id']);
        $subject->update($request->all());
        return redirect()->route('subjects.index')->with('success', 'تم تحديث المادة بنجاح');
    }
    public function destroy(Subject $subject) {
        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'تم حذف المادة بنجاح');
    }
}