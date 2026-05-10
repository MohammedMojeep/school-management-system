@extends('layouts.app')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-2xl mx-auto mt-10">
    <h2 class="text-xl font-bold text-gray-700 mb-6 border-b pb-3">تعديل صف: {{ $schoolClass->name }}</h2>
    <form action="{{ route('school_classes.update', $schoolClass) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-600 mb-2">اسم الصف</label>
            <input type="text" name="name" value="{{ $schoolClass->name }}" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-600 mb-2">المرحلة الدراسية</label>
            <select name="stage_id" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
                @foreach($stages as $stage)
                    <option value="{{ $stage->id }}" {{ $schoolClass->stage_id == $stage->id ? 'selected' : '' }}>{{ $stage->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('school_classes.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded hover:bg-gray-200 transition">إلغاء</a>
            <button type="submit" class="bg-[#7CB342] text-white px-6 py-2 rounded hover:bg-[#689f38] transition font-bold">تحديث</button>
        </div>
    </form>
</div>
@endsection