<?php
namespace App\Http\Controllers;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller {
    public function index(Request $request) { 
        $date = $request->date ?? date('Y-m-d');
        $attendances = Attendance::with('student.user')->where('date', $date)->get(); 
        return view('attendances.index', compact('attendances', 'date')); 
    }
    public function create() { $students = Student::with('user')->get(); return view('attendances.create', compact('students')); }
    public function store(Request $request) {
        $request->validate(['student_id' => 'required|exists:students,id', 'date' => 'required|date', 'status' => 'required|in:Present,Absent,Late']);
        
        Attendance::updateOrCreate(
            ['student_id' => $request->student_id, 'date' => $request->date],
            ['status' => $request->status]
        );
        
        return redirect()->route('attendances.index', ['date' => $request->date])->with('success', 'تم حفظ الحضور بنجاح');
    }
    // Simple direct delete to save time
    public function destroy(Attendance $attendance) {
        $attendance->delete();
        return redirect()->back()->with('success', 'تم الحذف بنجاح');
    }
}