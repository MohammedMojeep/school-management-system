<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول | School System</title>
    <!-- إضافة أيقونة للموقع -->
    <link rel="icon" href='data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="%237CB342" d="M256 0c-14.1 0-27.3 7.5-34.5 19.8L5.7 387.8C-2.5 401.8-1.6 419.5 8.2 432.4S35.6 448 52.8 448H459.2c17.2 0 32.3-10.5 42.1-23.4s10.7-30.6 2.5-44.6L290.5 19.8C283.3 7.5 270.1 0 256 0zM128 352h256v64H128v-64z"/></svg>' type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Cairo', sans-serif; background-color: #f4f6f9; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 bg-[#f4f6f9]">

<div class="max-w-md w-full bg-white rounded-lg shadow-[0_4px_20px_-3px_rgba(6,81,237,0.1)] overflow-hidden">
    <!-- Header -->
    <div class="p-8 pb-4 text-center">
        <!-- School Logo -->
        <div class="flex items-center justify-center mb-6">
            <i class="fa-solid fa-school text-[#7CB342] text-3xl"></i>
            <span class="text-3xl font-bold text-gray-700 ml-2">School<span class="text-[#7CB342]">Sys</span></span>
        </div>
        <h2 class="text-xl font-bold text-gray-800 mb-1">تسجيل الدخول</h2>
        <p class="text-sm text-gray-500">مرحباً بك مجدداً في لوحة تحكم النظام</p>
    </div>

    <!-- Form -->
    <div class="p-8 pt-4">
        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-gray-600 text-sm font-semibold mb-2" for="email">البريد الإلكتروني</label>
                <div class="relative">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-regular fa-envelope"></i>
                    </div>
                    <input type="email" id="email" name="email" value="{{ old('email', 'm@school.com') }}" class="w-full bg-gray-50 border border-gray-200 text-gray-800 rounded px-10 py-2.5 focus:outline-none focus:border-[#7CB342] focus:bg-white transition text-sm" required>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-gray-600 text-sm font-semibold" for="password">كلمة المرور</label>
                    <a href="#" class="text-xs text-blue-500 hover:underline font-semibold">نسيت كلمة المرور؟</a>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <input type="password" id="password" name="password" value="3" class="w-full bg-gray-50 border border-gray-200 text-gray-800 rounded px-10 py-2.5 focus:outline-none focus:border-[#7CB342] focus:bg-white transition text-sm" required>
                </div>
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-[#7CB342] bg-gray-100 border-gray-300 rounded focus:ring-[#7CB342] focus:ring-1">
                <label for="remember" class="text-gray-600 text-sm font-medium">تذكرني</label>
            </div>

            <button type="submit" class="w-full bg-[#7CB342] hover:bg-[#689f38] text-white font-bold py-2.5 px-4 rounded transition duration-300 mt-2 text-sm flex justify-center items-center gap-2">
                <span>دخول</span>
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </button>
        </form>
    </div>
</div>

<script type="module">
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    @if($errors->any())
        Toast.fire({
            icon: 'error',
            title: 'خطأ في تسجيل الدخول',
            text: '{{ $errors->first() }}'
        });
    @endif
</script>

</body>
</html>
