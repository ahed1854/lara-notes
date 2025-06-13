<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel Notes') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            // Initialize dark mode from localStorage before page load
            if (localStorage.getItem('darkMode') === 'true' || 
                (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>
    </head>
    <body class="antialiased transition-colors duration-200 bg-gray-100 dark:bg-gray-900">
        <div class="relative min-h-screen">
            <div class="relative pt-6 pb-16 sm:pb-24">
                <nav class="relative max-w-7xl mx-auto flex items-center justify-between px-4 sm:px-6">
                    <div class="flex items-center flex-1">
                        <div class="flex items-center justify-between w-full">
                            <a href="/" class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 transition-colors duration-200">
                                Notes
                            </a>
                            <div class="flex items-center space-x-4">
                                <!-- Dark mode toggle -->
                                <button @click="darkMode = !darkMode" class="p-2 rounded-md bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 transition-colors duration-200">
                                    <!-- Sun icon -->
                                    <svg x-show="darkMode" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <!-- Moon icon -->
                                    <svg x-show="!darkMode" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                    </svg>
                                </button>
                                <div class="hidden md:flex md:items-center md:space-x-4">
                                    @auth
                                        <a href="{{ route('dashboard') }}" class="text-base font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300 transition-colors duration-200">
                                            Dashboard
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="text-base font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300 transition-colors duration-200">
                                            Sign in
                                        </a>
                                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors duration-200">
                                            Get started
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>

                <main class="mt-16 mx-auto max-w-7xl px-4 sm:mt-24">
                    <div class="text-center">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white sm:text-5xl md:text-6xl transition-colors duration-200">
                            <span class="block">Your thoughts and reminders</span>
                            <span class="block text-indigo-600 dark:text-indigo-400">all in one place</span>
                        </h1>
                        <p class="mt-3 max-w-md mx-auto text-base text-gray-500 dark:text-gray-400 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl transition-colors duration-200">
                            A simple and elegant way to keep track of your notes and reminders. Stay organized and never miss important tasks.
                        </p>
                        <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                            @auth
                                <div class="rounded-md shadow">
                                    <a href="{{ route('dashboard') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 md:py-4 md:text-lg md:px-10 transition-colors duration-200">
                                        Go to Dashboard
                                    </a>
                                </div>
                            @else
                                <div class="rounded-md shadow">
                                    <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 md:py-4 md:text-lg md:px-10 transition-colors duration-200">
                                        Get started
                                    </a>
                                </div>
                                <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3">
                                    <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 dark:text-indigo-400 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 md:py-4 md:text-lg md:px-10 transition-colors duration-200">
                                        Sign in
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </main>
            </div>

            <!-- Feature section -->
            <div class="relative bg-white dark:bg-gray-800 py-16 sm:py-24 lg:py-32 transition-colors duration-200">
                <div class="mx-auto max-w-md px-4 text-center sm:max-w-3xl sm:px-6 lg:max-w-7xl lg:px-8">
                    <h2 class="text-base font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 transition-colors duration-200">Features</h2>
                    <p class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight sm:text-4xl transition-colors duration-200">
                        Everything you need to stay organized
                    </p>
                    <div class="mt-12">
                        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                            <!-- Notes Feature -->
                            <div class="pt-6">
                                <div class="flow-root bg-gray-50 dark:bg-gray-700 rounded-lg px-6 pb-8 transition-colors duration-200">
                                    <div class="-mt-6">
                                        <div class="inline-flex items-center justify-center p-3 bg-indigo-500 dark:bg-indigo-600 rounded-md shadow-lg">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </div>
                                        <h3 class="mt-8 text-lg font-medium text-gray-900 dark:text-white tracking-tight transition-colors duration-200">Notes</h3>
                                        <p class="mt-5 text-base text-gray-500 dark:text-gray-400 transition-colors duration-200">
                                            Create, edit, and organize your notes with a beautiful and intuitive interface.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Reminders Feature -->
                            <div class="pt-6">
                                <div class="flow-root bg-gray-50 dark:bg-gray-700 rounded-lg px-6 pb-8 transition-colors duration-200">
                                    <div class="-mt-6">
                                        <div class="inline-flex items-center justify-center p-3 bg-indigo-500 dark:bg-indigo-600 rounded-md shadow-lg">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <h3 class="mt-8 text-lg font-medium text-gray-900 dark:text-white tracking-tight transition-colors duration-200">Reminders</h3>
                                        <p class="mt-5 text-base text-gray-500 dark:text-gray-400 transition-colors duration-200">
                                            Set reminders for important tasks and never miss a deadline again.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Trash Feature -->
                            <div class="pt-6">
                                <div class="flow-root bg-gray-50 dark:bg-gray-700 rounded-lg px-6 pb-8 transition-colors duration-200">
                                    <div class="-mt-6">
                                        <div class="inline-flex items-center justify-center p-3 bg-indigo-500 dark:bg-indigo-600 rounded-md shadow-lg">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </div>
                                        <h3 class="mt-8 text-lg font-medium text-gray-900 dark:text-white tracking-tight transition-colors duration-200">Trash</h3>
                                        <p class="mt-5 text-base text-gray-500 dark:text-gray-400 transition-colors duration-200">
                                            Safely delete and restore your notes and reminders with our trash feature.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Labels Feature -->
                            <div class="pt-6">
                                <div class="flow-root bg-gray-50 dark:bg-gray-700 rounded-lg px-6 pb-8 transition-colors duration-200">
                                    <div class="-mt-6">
                                        <div class="inline-flex items-center justify-center p-3 bg-indigo-500 dark:bg-indigo-600 rounded-md shadow-lg">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                        </div>
                                        <h3 class="mt-8 text-lg font-medium text-gray-900 dark:text-white tracking-tight transition-colors duration-200">Labels</h3>
                                        <p class="mt-5 text-base text-gray-500 dark:text-gray-400 transition-colors duration-200">
                                            Organize your notes and reminders with customizable color-coded labels.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        @if (session('success') || session('error') || session('status'))
        <div x-data="{ notifications: [] }" 
             x-init="() => {
                @if (session('success'))
                    notifications.push({ id: Date.now(), message: '{{ session('success') }}', type: 'success' });
                @endif
                @if (session('error'))
                    notifications.push({ id: Date.now(), message: '{{ session('error') }}', type: 'error' });
                @endif
                @if (session('status'))
                    notifications.push({ id: Date.now(), message: '{{ session('status') }}', type: 'success' });
                @endif
                notifications.forEach(notification => {
                    setTimeout(() => {
                        notifications = notifications.filter(n => n.id !== notification.id);
                    }, 5000);
                });
             }">
            <template x-for="notification in notifications" :key="notification.id">
                <div x-show="true"
                     x-transition:enter="transform ease-out duration-300 transition"
                     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed bottom-0 right-0 m-6 w-full max-w-sm overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 transition-colors duration-200"
                     :class="{
                         'bg-white dark:bg-gray-800': notification.type === 'success',
                         'bg-red-50 dark:bg-red-900': notification.type === 'error'
                     }">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <template x-if="notification.type === 'success'">
                                    <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </template>
                                <template x-if="notification.type === 'error'">
                                    <svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                </template>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <p class="text-sm font-medium transition-colors duration-200" :class="{
                                    'text-gray-900 dark:text-gray-100': notification.type === 'success',
                                    'text-red-900 dark:text-red-100': notification.type === 'error'
                                }" x-text="notification.message"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        @endif
    </body>
</html>
