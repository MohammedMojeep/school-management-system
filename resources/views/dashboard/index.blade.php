@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2 class="text-xl font-bold text-gray-700">لوحة التحكم الرئيسية</h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white rounded-sm shadow-sm border-t-2 border-t-blue-500 p-4 relative flex flex-col justify-between min-h-[120px]">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-gray-500 mb-2">إجمالي الطلاب</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $studentsCount ?? 0 }}</h3>
            </div>
            <div class="text-blue-500 text-5xl">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
        </div>
        <div class="mt-4 text-left border-t pt-2 border-gray-100 flex items-center justify-end">
            <a href="{{ route('students.index') }}" class="text-xs text-gray-500 hover:text-gray-700 font-medium flex items-center gap-1">
                <i class="fa-solid fa-chart-bar"></i> عرض البيانات
            </a>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white rounded-sm shadow-sm border-t-2 border-t-green-600 p-4 relative flex flex-col justify-between min-h-[120px]">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-gray-500 mb-2">إجمالي المعلمين</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $teachersCount ?? 0 }}</h3>
            </div>
            <div class="text-green-600 text-5xl">
                <i class="fa-solid fa-chalkboard-teacher"></i>
            </div>
        </div>
        <div class="mt-4 text-left border-t pt-2 border-gray-100 flex items-center justify-end">
            <a href="{{ route('teachers.index') }}" class="text-xs text-gray-500 hover:text-gray-700 font-medium flex items-center gap-1">
                <i class="fa-solid fa-chart-bar"></i> عرض البيانات
            </a>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white rounded-sm shadow-sm border-t-2 border-t-yellow-400 p-4 relative flex flex-col justify-between min-h-[120px]">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-gray-500 mb-2">إجمالي الصفوف</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $classesCount ?? 0 }}</h3>
            </div>
            <div class="text-yellow-400 text-5xl">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>
        <div class="mt-4 text-left border-t pt-2 border-gray-100 flex items-center justify-end">
            <a href="{{ route('school_classes.index') }}" class="text-xs text-gray-500 hover:text-gray-700 font-medium flex items-center gap-1">
                <i class="fa-solid fa-chart-bar"></i> عرض البيانات
            </a>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="bg-white rounded-sm shadow-sm border-t-2 border-t-red-500 p-4 relative flex flex-col justify-between min-h-[120px]">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-gray-500 mb-2">نسبة الحضور اليوم</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $attendancePercentage ?? 0 }}%</h3>
            </div>
            <div class="text-red-500 text-5xl">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </div>
        <div class="mt-4 text-left border-t pt-2 border-gray-100 flex items-center justify-end">
            <a href="{{ route('attendances.index') }}" class="text-xs text-gray-500 hover:text-gray-700 font-medium flex items-center gap-1">
                <i class="fa-solid fa-chart-bar"></i> التقارير
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-sm shadow-sm p-0 overflow-hidden mt-6">
    <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50">
        <h3 class="text-lg font-bold text-gray-600">آخر الطلاب المضافين</h3>
    </div>

    <div class="overflow-x-auto p-4">
        <table class="w-full text-sm text-center">
            <thead class="bg-primary text-white font-bold">
                <tr>
                    <th class="py-3 px-4 font-semibold rounded-tr">#</th>
                    <th class="py-3 px-4 font-semibold">اسم الطالب</th>
                    <th class="py-3 px-4 font-semibold">المرحلة الدراسية</th>
                    <th class="py-3 px-4 font-semibold">الصف الدراسي</th>
                    <th class="py-3 px-4 font-semibold rounded-tl">تاريخ الإضافة</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $latestStudents = \App\Models\Student::with(['user', 'stage', 'schoolClass'])->latest()->take(5)->get();
                @endphp
                @forelse($latestStudents as $student)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 text-gray-500">{{ $loop->iteration }}</td>
                    <td class="py-3 px-4 text-gray-700">{{ $student->user->name }}</td>
                    <td class="py-3 px-4 text-green-600 font-medium">{{ $student->stage->name ?? '-' }}</td>
                    <td class="py-3 px-4 text-blue-600 font-medium">{{ $student->schoolClass->name ?? '-' }}</td>
                    <td class="py-3 px-4 text-gray-500" dir="ltr">{{ $student->created_at->format('Y-m-d') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-6 text-gray-500">لا يوجد طلاب مضافين مؤخراً</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

