<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام المدرسة | School System</title>
    <!-- إضافة أيقونة للموقع -->
    <link rel="icon" href='data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="%237CB342" d="M256 0c-14.1 0-27.3 7.5-34.5 19.8L5.7 387.8C-2.5 401.8-1.6 419.5 8.2 432.4S35.6 448 52.8 448H459.2c17.2 0 32.3-10.5 42.1-23.4s10.7-30.6 2.5-44.6L290.5 19.8C283.3 7.5 270.1 0 256 0zM128 352h256v64H128v-64z"/></svg>' type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Cairo', sans-serif; background-color: #f4f6f9; }
        .sidebar-item { transition: all 0.3s ease; border-right: 3px solid transparent; }
        .sidebar-item:hover { background-color: rgba(255,255,255,0.05); }
        .sidebar-item.active { background-color: rgba(255,255,255,0.05); border-right-color: #7CB342; color: white;}
        /* Hide scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }
    </style>
</head>
<body class="text-gray-800 antialiased overflow-hidden">

<div class="flex h-screen w-full">
    
    <!-- Sidebar -->
    <aside class="w-[260px] bg-primary text-gray-300 flex-shrink-0 flex flex-col hidden md:flex z-20 transition-all duration-300 relative">
        <nav class="flex-1 overflow-y-auto sidebar-scroll py-4 mt-2">
            
            @if(auth()->user() && auth()->user()->isAdmin())
            <a href="{{ route('dashboard') }}" class="sidebar-item flex items-center justify-between px-5 py-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-house w-5 text-center text-sm"></i>
                    <span class="text-[15px]">الرئيسية</span>
                </div>
            </a>
            @endif
            
            @if(auth()->user() && auth()->user()->isAdmin())
            <a href="{{ route('stages.index') }}" class="sidebar-item flex items-center justify-between px-5 py-3 {{ request()->routeIs('stages.*') ? 'active' : '' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-building w-5 text-center text-sm"></i>
                    <span class="text-[15px]">المراحل الدراسية</span>
                </div>
            </a>

            <a href="{{ route('school_classes.index') }}" class="sidebar-item flex items-center justify-between px-5 py-3 {{ request()->routeIs('school_classes.*') ? 'active' : '' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-layer-group w-5 text-center text-sm"></i>
                    <span class="text-[15px]">الصفوف</span>
                </div>
            </a>
            
            <a href="{{ route('teachers.index') }}" class="sidebar-item flex items-center justify-between px-5 py-3 {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-chalkboard-teacher w-5 text-center text-sm"></i>
                    <span class="text-[15px]">المعلمين</span>
                </div>
            </a>
            @endif

            @if(auth()->user() && (auth()->user()->isAdmin() || auth()->user()->isTeacher()))
            <a href="{{ route('students.index') }}" class="sidebar-item flex items-center justify-between px-5 py-3 {{ request()->routeIs('students.*') ? 'active' : '' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-user-graduate w-5 text-center text-sm"></i>
                    <span class="text-[15px]">الطلاب</span>
                </div>
            </a>
            @endif

            <a href="{{ route('subjects.index') }}" class="sidebar-item flex items-center justify-between px-5 py-3 {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-book w-5 text-center text-sm"></i>
                    <span class="text-[15px]">المواد الدراسية</span>
                </div>
            </a>

            <a href="{{ route('attendances.index') }}" class="sidebar-item flex items-center justify-between px-5 py-3 {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-calendar-check w-5 text-center text-sm"></i>
                    <span class="text-[15px]">الحضور والغياب</span>
                </div>
            </a>
            
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="mt-4 px-5">
                @csrf
                <button type="button" onclick="confirmLogout()" class="w-full bg-red-500/10 hover:bg-red-500/20 text-red-400 py-2 rounded transition text-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-sign-out-alt"></i> تسجيل الخروج
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <!-- Top Navbar -->
        <header class="h-[60px] bg-white shadow-[0_2px_10px_-3px_rgba(6,81,237,0.3)] flex items-center justify-between px-6 z-10">
            <!-- Left side icons -->
            <div class="flex items-center gap-5 text-gray-500">
                <div class="flex items-center gap-2 mr-2">
                    <!-- User profile -->
                    <div class="w-8 h-8 rounded-full bg-gray-200 overflow-hidden ml-2 flex items-center justify-center text-gray-600">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">{{ auth()->user()->name ?? 'مستخدم' }}</span>
                    <span class="text-xs bg-[#7CB342] text-white px-2 py-0.5 rounded mr-2">{{ auth()->user()->role->name ?? '' }}</span>
                </div>
            </div>

            <!-- Right side (Logo and toggle) -->
            <div class="flex items-center gap-4">
                <div class="relative hidden sm:block">
                    <input type="text" id="searchInput" onkeyup="filterTables()" placeholder="بحث سريع..." class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-full pl-10 pr-4 py-1.5 focus:outline-none focus:border-[#7CB342] transition w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-search text-gray-400"></i>
                    </div>
                </div>
                <div class="flex items-center gap-1 border-r pr-4 border-gray-200">
                    <!-- School Logo -->
                    <div class="flex items-center">
                        <i class="fa-solid fa-school text-[#7CB342] text-2xl ml-2"></i>
                        <span class="text-xl font-bold text-gray-700">School<span class="text-[#7CB342]">Sys</span></span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#f4f6f9] p-6">
            @yield('content')
        </main>
    </div>
</div>

<script type="module">
    // Make sure Swal is available then fire toasts
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

    // Make functions globally available
    window.filterTables = function() {
        let input = document.getElementById("searchInput");
        let filter = input.value.toUpperCase();
        let tables = document.querySelectorAll("table");
        
        tables.forEach(table => {
            let trs = table.getElementsByTagName("tr");
            for (let i = 1; i < trs.length; i++) {
                let tds = trs[i].getElementsByTagName("td");
                let found = false;
                for (let j = 0; j < tds.length; j++) {
                    if (tds[j]) {
                        let txtValue = tds[j].textContent || tds[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                if(trs[i].classList.contains("border-b")) { // only hide data rows
                    trs[i].style.display = found ? "" : "none";
                }
            }
        });
    }

    window.confirmDelete = function(formId, textMsg = "لن تتمكن من التراجع عن هذه الخطوة! سيتم حذف السجل بالكامل.") {
        Swal.fire({
            title: 'هل أنت متأكد من الحذف؟',
            text: textMsg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }

    window.confirmLogout = function() {
        Swal.fire({
            title: 'تسجيل الخروج',
            text: "هل أنت متأكد أنك تريد تسجيل الخروج من النظام؟",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'نعم، تسجيل الخروج',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }

    @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: '{{ session("success") }}'
        });
    @endif

    @if($errors->any())
        Toast.fire({
            icon: 'error',
            title: 'يوجد خطأ في البيانات المدخلة',
            text: '{{ $errors->first() }}'
        });
    @endif
</script>

</body>
</html>
