@extends('layouts.app')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold text-gray-700">سجل الحضور والغياب</h2>
    @if(auth()->user()->isAdmin())
<a href="{{ route('attendances.create') }}" class="bg-[#7CB342] hover:bg-[#689f38] text-white px-4 py-2 rounded text-sm font-semibold transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> تسجيل حضور جديد
    </a>
@endif
</div>

<div class="bg-white rounded shadow-sm p-4 mb-6">
    <form action="{{ route('attendances.index') }}" method="GET" class="flex gap-4 items-end">
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">اختر التاريخ</label>
            <input type="date" name="date" value="{{ $date }}" class="border border-gray-200 rounded px-3 py-2 outline-none focus:border-[#7CB342]">
        </div>
        <button type="submit" class="bg-[#7CB342] text-white px-6 py-2 rounded hover:bg-opacity-90 transition font-bold">عرض</button>
    </form>
</div>

<div class="bg-white rounded shadow-sm overflow-hidden">
    <table class="w-full text-sm text-center">
        <thead class="bg-primary text-white font-bold">
            <tr><th class="py-3 px-4 font-semibold">#</th><th class="py-3 px-4 font-semibold">اسم الطالب</th><th class="py-3 px-4 font-semibold">التاريخ</th><th class="py-3 px-4 font-semibold">الحالة</th>@if(auth()->user()->isAdmin())
<th class="py-3 px-4 font-semibold">إلغاء</th>
@endif</tr>
        </thead>
        <tbody>
            @forelse($attendances as $att)
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-3 px-4 text-gray-500">{{ $loop->iteration }}</td>
                <td class="py-3 px-4 text-gray-700 font-bold">{{ $att->student->user->name ?? '-' }}</td>
                <td class="py-3 px-4 text-gray-500">{{ $att->date }}</td>
                <td class="py-3 px-4 font-bold 
                    {{ $att->status == 'Present' ? 'text-green-600' : ($att->status == 'Absent' ? 'text-red-600' : 'text-yellow-600') }}">
                    {{ $att->status == 'Present' ? 'حاضر' : ($att->status == 'Absent' ? 'غائب' : 'متأخر') }}
                </td>
                @if(auth()->user()->isAdmin())
<td class="py-3 px-4 flex justify-center gap-2">
                    <form action="{{ route('attendances.destroy', $att) }}" method="POST" id="delete-form-{{ $loop->iteration }}" >
                        @csrf @method('DELETE')
                        <button type="button" onclick="confirmDelete('delete-form-{{ $loop->iteration }}', 'لن تتمكن من التراجع عن هذه الخطوة! سيتم حذف السجل بالكامل.')" class="bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200 transition"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
@endif
            </tr>
            @empty
            <tr><td colspan="5" class="py-6 text-gray-500">لا يوجد سجلات حضور لهذا اليوم</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection