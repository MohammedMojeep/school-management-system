@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-700">إدارة المراحل الدراسية</h2>
    @if(auth()->user()->isAdmin())
<a href="{{ route('stages.create') }}" class="bg-[#7CB342] hover:bg-[#689f38] text-white px-4 py-2 rounded text-sm font-semibold transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> إضافة مرحلة
    </a>
@endif
</div>
<div class="bg-white rounded shadow-sm overflow-hidden">
    <table class="w-full text-sm text-center">
        <thead class="bg-primary text-white font-bold">
            <tr><th class="py-3 px-4 font-semibold">#</th><th class="py-3 px-4 font-semibold">اسم المرحلة</th><th class="py-3 px-4 font-semibold">ملاحظات</th><th class="py-3 px-4 font-semibold">العمليات</th></tr>
        </thead>
        <tbody>
            @forelse($stages as $stage)
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-3 px-4 text-gray-500">{{ $loop->iteration }}</td>
                <td class="py-3 px-4 text-gray-700 font-bold">{{ $stage->name }}</td>
                <td class="py-3 px-4 text-gray-500">{{ $stage->notes ?? '-' }}</td>
                @if(auth()->user()->isAdmin())
<td class="py-3 px-4 flex justify-center gap-2">
                    <a href="{{ route('stages.edit', $stage) }}" class="bg-blue-100 text-blue-600 px-3 py-1 rounded hover:bg-blue-200 transition"><i class="fa-solid fa-pen"></i></a>
                    <form action="{{ route('stages.destroy', $stage) }}" method="POST" id="delete-form-{{ $loop->iteration }}" >
                        @csrf @method('DELETE')
                        <button type="button" onclick="confirmDelete('delete-form-{{ $loop->iteration }}', 'لن تتمكن من التراجع عن هذه الخطوة! سيتم حذف السجل بالكامل.')" class="bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200 transition"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
@endif
            </tr>
            @empty
            <tr><td colspan="4" class="py-6 text-gray-500">لا توجد مراحل دراسية</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection