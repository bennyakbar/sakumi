<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Keuangan Sekolah') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased bg-gray-50 text-slate-800">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex flex-col md:flex-row">

        <!-- Mobile Header -->
        <div class="md:hidden flex items-center justify-between bg-white border-b border-gray-200 p-4">
            <div class="font-bold text-xl text-indigo-600">SAKU MI</div>
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
        </div>

        <!-- Sidebar -->
        <aside :class="{'block': sidebarOpen, 'hidden': !sidebarOpen}"
            class="hidden md:flex flex-col w-full md:w-64 bg-slate-900 text-white flex-shrink-0 h-screen sticky top-0 transition-all duration-300 z-50">
            <div class="p-6 border-b border-slate-800 flex items-center justify-center">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain mr-3">
                <h1 class="text-2xl font-bold tracking-wider text-indigo-400">SAKU MI</h1>
            </div>

            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-2">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                </path>
                            </svg>
                            Dashboard
                        </a>
                    </li>

                    <li class="px-4 py-2 mt-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Master Data
                    </li>

                    <li>
                        <a href="{{ route('master-data.classes.index') }}"
                            class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('master-data.classes.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            Kelas
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master-data.student-categories.index') }}"
                            class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('master-data.student-categories.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                            Kategori Siswa
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master-data.students.index') }}"
                            class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('master-data.students.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            Data Siswa
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master-data.fee-types.index') }}"
                            class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('master-data.fee-types.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            Jenis Biaya
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master-data.fee-matrix.index') }}"
                            class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('master-data.fee-matrix.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4">
                                </path>
                            </svg>
                            Matriks Biaya
                        </a>
                    </li>

                    <li class="px-4 py-2 mt-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Keuangan
                    </li>

                    <li>
                        <a href="{{ route('finance.transactions.index') }}"
                            class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('finance.transactions.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            Transaksi Pembayaran
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('finance.reports.index') }}"
                            class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800 transition-colors {{ request()->routeIs('finance.reports.*') ? 'bg-indigo-600 text-white' : 'text-slate-300' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Laporan Keuangan
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="p-4 border-t border-slate-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center px-4 py-2 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
            <!-- Topbar -->
            <header class="bg-white shadow-sm sticky top-0 z-40">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        @yield('header')
                    </h2>

                    <div class="flex items-center space-x-4">
                        <div class="text-sm text-right hidden sm:block">
                            <div class="font-medium text-gray-900">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500 capitalize">
                                {{ Auth::user()->roles->pluck('name')->join(', ') }}
                            </div>
                        </div>
                        <div
                            class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border border-indigo-200">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    {{ session('error') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>