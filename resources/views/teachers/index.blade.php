@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-700">إدارة المعلمين</h2>
    @if(auth()->user()->isAdmin())
<a href="{{ route('teachers.create') }}" class="bg-[#7CB342] hover:bg-[#689f38] text-white px-4 py-2 rounded text-sm font-semibold transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> إضافة معلم
    </a>
@endif
</div>
<div class="bg-white rounded shadow-sm overflow-hidden">
    <table class="w-full text-sm text-center">
        <thead class="bg-primary text-white font-bold">
            <tr><th class="py-3 px-4 font-semibold">#</th><th class="py-3 px-4 font-semibold">اسم المعلم</th><th class="py-3 px-4 font-semibold">البريد الإلكتروني</th><th class="py-3 px-4 font-semibold">رقم الهاتف</th><th class="py-3 px-4 font-semibold">العمليات</th></tr>
        </thead>
        <tbody>
            @forelse($teachers as $teacher)
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-3 px-4 text-gray-500">{{ $loop->iteration }}</td>
                <td class="py-3 px-4 text-gray-700 font-bold">{{ $teacher->user->name ?? '-' }}</td>
                <td class="py-3 px-4 text-gray-500">{{ $teacher->user->email ?? '-' }}</td>
                <td class="py-3 px-4 text-gray-500">{{ $teacher->phone_number ?? '-' }}</td>
                @if(auth()->user()->isAdmin())
<td class="py-3 px-4 flex justify-center gap-2">
                    <a href="{{ route('teachers.edit', $teacher) }}" class="bg-blue-100 text-blue-600 px-3 py-1 rounded hover:bg-blue-200 transition"><i class="fa-solid fa-pen"></i></a>
                    <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" id="delete-form-{{ $loop->iteration }}" >
                        @csrf @method('DELETE')
                        <button type="button" onclick="confirmDelete('delete-form-{{ $loop->iteration }}', 'سيتم حذف حساب المستخدم أيضاً!')" class="bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200 transition"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
@endif
            </tr>
            @empty
            <tr><td colspan="5" class="py-6 text-gray-500">لا يوجد معلمين</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection