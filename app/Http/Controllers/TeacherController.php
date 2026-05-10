<?php
namespace App\Http\Controllers;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller {
    public function index() { $teachers = Teacher::with('user')->latest()->get(); return view('teachers.index', compact('teachers')); }
    public function create() { return view('teachers.create'); }
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            $role = Role::firstOrCreate(['name' => 'Teacher']);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role_id' => $role->id
            ]);
            Teacher::create([
                'user_id' => $user->id,
                'phone_number' => $request->phone_number,
                'address' => $request->address
            ]);
        });

        return redirect()->route('teachers.index')->with('success', 'تمت إضافة المعلم بنجاح');
    }
    public function edit(Teacher $teacher) { return view('teachers.edit', compact('teacher')); }
    public function update(Request $request, Teacher $teacher) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->user_id,
            'password' => 'nullable|string|min:8',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request, $teacher) {
            $user = $teacher->user;
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = $request->password;
            }
            $user->save();
            
            $teacher->update([
                'phone_number' => $request->phone_number,
                'address' => $request->address
            ]);
        });

        return redirect()->route('teachers.index')->with('success', 'تم تحديث بيانات المعلم بنجاح');
    }
    public function destroy(Teacher $teacher) {
        DB::transaction(function () use ($teacher) {
            $userId = $teacher->user_id;
            $teacher->delete();
            User::where('id', $userId)->delete();
        });
        return redirect()->route('teachers.index')->with('success', 'تم حذف المعلم بنجاح');
    }
}