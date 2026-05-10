@extends('layouts.app')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-3xl mx-auto mt-10">
    <h2 class="text-xl font-bold text-gray-700 mb-6 border-b pb-3">إضافة معلم جديد</h2>
    <form action="{{ route('teachers.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">اسم المعلم</label>
                <input type="text" name="name" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">البريد الإلكتروني</label>
                <input type="email" name="email" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">كلمة المرور (للدخول للنظام)</label>
                <input type="password" name="password" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">رقم الهاتف</label>
                <input type="text" name="phone_number" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]">
            </div>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-600 mb-2">العنوان</label>
            <input type="text" name="address" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]">
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('teachers.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded hover:bg-gray-200 transition">إلغاء</a>
            <button type="submit" class="bg-[#7CB342] text-white px-6 py-2 rounded hover:bg-[#689f38] transition font-bold">حفظ</button>
        </div>
    </form>
</div>
@endsection