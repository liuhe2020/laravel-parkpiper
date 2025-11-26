<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'ParkPiper'))</title>

     <!-- Theme script - MUST be before CSS to prevent flash -->
    <script>
        // Immediately check and apply theme before page renders
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="p-4 sm:px-6 lg:px-8 bg-white dark:bg-gray-900 transition-colors duration-300">
    <header class="flex items-center gap-6 w-full justify-between">
        {{-- filepath: resources/views/layouts/app.blade.php --}}
        <nav class="flex items-center gap-4 text-sm font-medium text-gray-600 dark:text-gray-300 flex-wrap">
            <a href="{{ route('home') }}"
            class="{{ request()->routeIs('home') ? 'text-indigo-600 dark:text-indigo-400' : 'hover:text-indigo-600 dark:hover:text-indigo-400 transition' }}">
                 Check Permit
            </a>
            <a href="{{ route('permits.issue') }}"
            class="{{ request()->routeIs('permits.issue') ? 'text-indigo-600 dark:text-indigo-400' : 'hover:text-indigo-600 dark:hover:text-indigo-400 transition' }}">
                Issue Permit
            </a>
            <a href="{{ route('permits.index') }}"
            class="{{ request()->routeIs('permits.index') ? 'text-indigo-600 dark:text-indigo-400' : 'hover:text-indigo-600 dark:hover:text-indigo-400 transition' }}">
                View Permits
            </a>
            <a href="{{ route('documentation') }}"
            class="{{ request()->routeIs('documentation') ? 'text-indigo-600 dark:text-indigo-400' : 'hover:text-indigo-600 dark:hover:text-indigo-400 transition' }}">
                API Documentation
            </a>
        </nav>
        <!-- Theme Toggle Button -->
        <button 
            id="themeToggle"
            class="p-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all"
            title='Toggle light/dark theme'
            aria-label="Toggle theme"
        >
            <svg id="sunIcon" class="w-4 h-4 text-gray-700 dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
            </svg>
            <svg id="moonIcon" class="w-4 h-4 text-gray-300 hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
            </svg>
        </button>
    </header>
    
    <main>
    @yield('content')
    </main>
</body>
</html>