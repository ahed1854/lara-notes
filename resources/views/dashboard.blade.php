<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Recent Notes -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Notes</h3>
                            <a href="{{ route('notes.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">View All</a>
                        </div>
                        <div class="space-y-4">
                            @forelse ($notes as $note)
                                <div class="p-4 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm">
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $note->title ?: 'Untitled' }}</h4>
                                    <p class="mt-1 text-gray-600 dark:text-gray-400">{{ Str::limit($note->content, 100) }}</p>
                                    <div class="mt-2 flex items-center justify-between">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $note->created_at->diffForHumans() }}</span>
                                        @if($note->labels->count() > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($note->labels as $label)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs" 
                                                        style="background-color: {{ $label->color }}20; color: {{ $label->color }}">
                                                        <span class="w-2 h-2 rounded-full mr-1" style="background-color: {{ $label->color }}"></span>
                                                        {{ $label->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">No notes yet. <a href="{{ route('notes.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Create one</a>.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Upcoming Reminders -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Upcoming Reminders</h3>
                            <a href="{{ route('reminders.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">View All</a>
                        </div>
                        <div class="space-y-4">
                            @forelse ($reminders as $reminder)
                                <div class="p-4 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $reminder->title }}</h4>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($reminder->reminder_at)->format('M d, Y h:i A') }}</span>
                                    </div>
                                    @if ($reminder->description)
                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $reminder->description }}</p>
                                    @endif
                                    @if($reminder->labels->count() > 0)
                                        <div class="flex flex-wrap gap-1 mt-2">
                                            @foreach($reminder->labels as $label)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs" 
                                                    style="background-color: {{ $label->color }}20; color: {{ $label->color }}">
                                                    <span class="w-2 h-2 rounded-full mr-1" style="background-color: {{ $label->color }}"></span>
                                                    {{ $label->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">No upcoming reminders. <a href="{{ route('reminders.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Create one</a>.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg md:col-span-2">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-indigo-50 dark:bg-indigo-900/50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-indigo-600 dark:text-indigo-300">Total Notes</h4>
                                <p class="mt-2 text-3xl font-semibold text-indigo-900 dark:text-indigo-100">{{ auth()->user()->notes()->count() }}</p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-green-600 dark:text-green-300">Active Reminders</h4>
                                <p class="mt-2 text-3xl font-semibold text-green-900 dark:text-green-100">{{ auth()->user()->reminders()->where('is_completed', false)->count() }}</p>
                            </div>
                            <div class="bg-yellow-50 dark:bg-yellow-900/50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-yellow-600 dark:text-yellow-300">Completed Reminders</h4>
                                <p class="mt-2 text-3xl font-semibold text-yellow-900 dark:text-yellow-100">{{ auth()->user()->reminders()->where('is_completed', true)->count() }}</p>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-purple-600 dark:text-purple-300">Pinned Notes</h4>
                                <p class="mt-2 text-3xl font-semibold text-purple-900 dark:text-purple-100">{{ auth()->user()->notes()->where('is_pinned', true)->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
