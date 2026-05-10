@extends('layouts.app')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-2xl mx-auto mt-10">
    <h2 class="text-xl font-bold text-gray-700 mb-6 border-b pb-3">إضافة مادة جديدة</h2>
    <form action="{{ route('subjects.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-600 mb-2">اسم المادة</label>
            <input type="text" name="name" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-600 mb-2">الصف الدراسي</label>
            <select name="school_class_id" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
                <option value="">-- اختر الصف --</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-600 mb-2">المعلم</label>
            <select name="teacher_id" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
                <option value="">-- اختر المعلم --</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->user->name ?? 'معلم' }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('subjects.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded hover:bg-gray-200 transition">إلغاء</a>
            <button type="submit" class="bg-[#7CB342] text-white px-6 py-2 rounded hover:bg-[#689f38] transition font-bold">حفظ</button>
        </div>
    </form>
</div>
@endsection