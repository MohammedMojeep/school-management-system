<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Attendance;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('subjects.index');
        }

        $studentsCount = Student::count();
        $teachersCount = Teacher::count();
        $classesCount = SchoolClass::count();
        
        $totalStudents = $studentsCount > 0 ? $studentsCount : 1; // avoid division by zero
        $attendanceToday = Attendance::where('date', date('Y-m-d'))->where('status', 'Present')->count();
        
        // Simple percentage of present students today relative to total students
        $attendancePercentage = round(($attendanceToday / $totalStudents) * 100);

        return view('dashboard.index', compact('studentsCount', 'teachersCount', 'classesCount', 'attendancePercentage'));
    }
}
