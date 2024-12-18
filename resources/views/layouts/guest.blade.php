<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
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
    <body class="font-sans text-gray-900 dark:text-gray-100 antialiased transition-colors duration-200">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
            <!-- Dark mode toggle -->
            <div class="absolute top-4 right-4">
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
            </div>

            <div>
                <a href="/" class="flex items-center">
                    <span class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-200">Notes</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md dark:shadow-gray-700 overflow-hidden sm:rounded-lg transition-colors duration-200">
                {{ $slot }}
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
