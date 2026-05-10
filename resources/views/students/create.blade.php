@extends('layouts.app')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-4xl mx-auto mt-6">
    <h2 class="text-xl font-bold text-gray-700 mb-6 border-b pb-3">إضافة طالب جديد</h2>
    <form action="{{ route('students.store') }}" method="POST">
        @csrf
        
        <h3 class="text-md font-bold text-gray-600 mb-3"><i class="fa-solid fa-user text-[#7CB342]"></i> بيانات الحساب</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-gray-50 p-4 rounded border border-gray-100">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">اسم الطالب (الكامل)</label>
                <input type="text" name="name" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">البريد الإلكتروني</label>
                <input type="email" name="email" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">كلمة المرور</label>
                <input type="password" name="password" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
            </div>
        </div>

        <h3 class="text-md font-bold text-gray-600 mb-3"><i class="fa-solid fa-graduation-cap text-[#7CB342]"></i> البيانات الأكاديمية</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-gray-50 p-4 rounded border border-gray-100">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">رقم الطالب الأكاديمي (ID)</label>
                <input type="text" name="student_id" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">المرحلة الدراسية</label>
                <select name="stage_id" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
                    <option value="">-- اختر المرحلة --</option>
                    @foreach($stages as $stage)
                        <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">الصف الدراسي</label>
                <select name="school_class_id" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
                    <option value="">-- اختر الصف --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <h3 class="text-md font-bold text-gray-600 mb-3"><i class="fa-solid fa-id-card text-[#7CB342]"></i> بيانات إضافية</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 bg-gray-50 p-4 rounded border border-gray-100">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">تاريخ الميلاد</label>
                <input type="date" name="date_of_birth" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">الجنس</label>
                <select name="gender" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]" required>
                    <option value="Male">ذكر</option>
                    <option value="Female">أنثى</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">رقم هاتف ولي الأمر</label>
                <input type="text" name="parent_phone" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">العنوان</label>
                <input type="text" name="address" class="w-full border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]">
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-4">
            <a href="{{ route('students.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded hover:bg-gray-200 transition">إلغاء</a>
            <button type="submit" class="bg-[#7CB342] text-white px-8 py-2 rounded hover:bg-[#689f38] transition font-bold">تسجيل الطالب</button>
        </div>
    </form>
</div>
@endsection