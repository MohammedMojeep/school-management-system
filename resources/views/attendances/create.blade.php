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
                @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->user->name ?? 'طالب' }}</option>
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