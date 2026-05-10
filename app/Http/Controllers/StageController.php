<?php
namespace App\Http\Controllers;
use App\Models\Stage;
use Illuminate\Http\Request;

class StageController extends Controller {
    public function index() { $stages = Stage::latest()->get(); return view('stages.index', compact('stages')); }
    public function create() { return view('stages.create'); }
    public function store(Request $request) {
        $request->validate(['name' => 'required|string|max:255', 'notes' => 'nullable|string']);
        Stage::create($request->all());
        return redirect()->route('stages.index')->with('success', 'تمت إضافة المرحلة بنجاح');
    }
    public function edit(Stage $stage) { return view('stages.edit', compact('stage')); }
    public function update(Request $request, Stage $stage) {
        $request->validate(['name' => 'required|string|max:255', 'notes' => 'nullable|string']);
        $stage->update($request->all());
        return redirect()->route('stages.index')->with('success', 'تم تحديث المرحلة بنجاح');
    }
    public function destroy(Stage $stage) {
        $stage->delete();
        return redirect()->route('stages.index')->with('success', 'تم حذف المرحلة بنجاح');
    }
}