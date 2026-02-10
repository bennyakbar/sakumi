<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-900 font-sans antialiased h-screen flex items-center justify-center">
    <div class="w-full max-w-sm bg-white rounded-xl shadow-2xl overflow-hidden p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-800">SAKU MI</h1>
            <p class="text-slate-500 mt-2">Sistem Administrasi & Keuangan MI</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 bg-red-50 text-red-600 p-3 rounded-lg text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" id="email" required autofocus
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all placeholder-slate-400"
                    placeholder="name@school.com">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all placeholder-slate-400"
                    placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember"
                        class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-slate-600">Remember me</span>
                </label>
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-lg transition-colors focus:ring-4 focus:ring-indigo-300">
                Sign In
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} School Financial System
        </div>
    </div>
</body>

</html>