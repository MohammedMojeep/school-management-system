@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-700">إدارة الطلاب</h2>
    @if(auth()->user()->isAdmin())
<a href="{{ route('students.create') }}" class="bg-[#7CB342] hover:bg-[#689f38] text-white px-4 py-2 rounded text-sm font-semibold transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> إضافة طالب
    </a>
@endif
</div>
<div class="bg-white rounded shadow-sm overflow-hidden">
    <table class="w-full text-sm text-center">
        <thead class="bg-primary text-white font-bold">
            <tr><th class="py-3 px-4 font-semibold">رقم الطالب</th><th class="py-3 px-4 font-semibold">الاسم</th><th class="py-3 px-4 font-semibold">المرحلة</th><th class="py-3 px-4 font-semibold">الصف</th><th class="py-3 px-4 font-semibold">الجنس</th><th class="py-3 px-4 font-semibold">العمليات</th></tr>
        </thead>
        <tbody>
            @forelse($students as $student)
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-3 px-4 text-gray-500 font-bold" dir="ltr">{{ $student->student_id }}</td>
                <td class="py-3 px-4 text-gray-700 font-bold">{{ $student->user->name ?? '-' }}</td>
                <td class="py-3 px-4 text-green-600">{{ $student->stage->name ?? '-' }}</td>
                <td class="py-3 px-4 text-blue-600">{{ $student->schoolClass->name ?? '-' }}</td>
                <td class="py-3 px-4 text-gray-500">{{ $student->gender == 'Male' ? 'ذكر' : 'أنثى' }}</td>
                @if(auth()->user()->isAdmin())
<td class="py-3 px-4 flex justify-center gap-2">
                    <a href="{{ route('students.edit', $student) }}" class="bg-blue-100 text-blue-600 px-3 py-1 rounded hover:bg-blue-200 transition"><i class="fa-solid fa-pen"></i></a>
                    <form action="{{ route('students.destroy', $student) }}" method="POST" id="delete-form-{{ $loop->iteration }}" >
                        @csrf @method('DELETE')
                        <button type="button" onclick="confirmDelete('delete-form-{{ $loop->iteration }}', 'سيتم حذف حساب المستخدم أيضاً!')" class="bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200 transition"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
@endif
            </tr>
            @empty
            <tr><td colspan="6" class="py-6 text-gray-500">لا يوجد طلاب مسجلين</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection