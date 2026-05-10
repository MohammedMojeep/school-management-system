<?php
namespace App\Http\Controllers;
use App\Models\SchoolClass;
use App\Models\Stage;
use Illuminate\Http\Request;

class SchoolClassController extends Controller {
    public function index() { $classes = SchoolClass::with('stage')->latest()->get(); return view('school_classes.index', compact('classes')); }
    public function create() { $stages = Stage::all(); return view('school_classes.create', compact('stages')); }
    public function store(Request $request) {
        $request->validate(['name' => 'required|string|max:255', 'stage_id' => 'required|exists:stages,id']);
        SchoolClass::create($request->all());
        return redirect()->route('school_classes.index')->with('success', 'تمت إضافة الصف بنجاح');
    }
    public function edit(SchoolClass $schoolClass) { $stages = Stage::all(); return view('school_classes.edit', compact('schoolClass', 'stages')); }
    public function update(Request $request, SchoolClass $schoolClass) {
        $request->validate(['name' => 'required|string|max:255', 'stage_id' => 'required|exists:stages,id']);
        $schoolClass->update($request->all());
        return redirect()->route('school_classes.index')->with('success', 'تم تحديث الصف بنجاح');
    }
    public function destroy(SchoolClass $schoolClass) {
        $schoolClass->delete();
        return redirect()->route('school_classes.index')->with('success', 'تم حذف الصف بنجاح');
    }
}