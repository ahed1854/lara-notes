<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Trash') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Trashed Notes -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">{{ __('Trashed Notes') }}</h3>
                        @if($notes->isNotEmpty())
                            <form action="{{ route('trash.destroy-all-notes') }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete all notes in trash? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 flex items-center">
                                    <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete All Notes
                                </button>
                            </form>
                        @endif
                    </div>
                    
                    @if($notes->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">{{ __('No notes in trash.') }}</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($notes as $note)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                                    <h4 class="font-semibold mb-2 text-gray-900 dark:text-gray-100">{{ $note->title ?: 'Untitled' }}</h4>
                                    <p class="text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($note->content, 100) }}</p>
                                    @if($note->labels->count() > 0)
                                        <div class="flex flex-wrap gap-1 mb-4">
                                            @foreach($note->labels as $label)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs" 
                                                    style="background-color: {{ $label->color }}20; color: {{ $label->color }}">
                                                    <span class="w-2 h-2 rounded-full mr-1" style="background-color: {{ $label->color }}"></span>
                                                    {{ $label->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="flex justify-end space-x-2">
                                        <form action="{{ route('trash.restore-note', $note->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                                </svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('trash.destroy-note', $note->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this note?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Trashed Reminders -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">{{ __('Trashed Reminders') }}</h3>
                        @if($reminders->isNotEmpty())
                            <form action="{{ route('trash.destroy-all-reminders') }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete all reminders in trash? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 flex items-center">
                                    <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete All Reminders
                                </button>
                            </form>
                        @endif
                    </div>
                    
                    @if($reminders->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">{{ __('No reminders in trash.') }}</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($reminders as $reminder)
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                                    <h4 class="font-semibold mb-2 text-gray-900 dark:text-gray-100">{{ $reminder->title }}</h4>
                                    <p class="text-gray-600 dark:text-gray-300 mb-2">{{ $reminder->description }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Due: {{ $reminder->reminder_at }}</p>
                                    @if($reminder->labels->count() > 0)
                                        <div class="flex flex-wrap gap-1 mb-4">
                                            @foreach($reminder->labels as $label)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs" 
                                                    style="background-color: {{ $label->color }}20; color: {{ $label->color }}">
                                                    <span class="w-2 h-2 rounded-full mr-1" style="background-color: {{ $label->color }}"></span>
                                                    {{ $label->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="flex justify-end space-x-2">
                                        <form action="{{ route('trash.restore-reminder', $reminder->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                                </svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('trash.destroy-reminder', $reminder->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this reminder?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Show session messages
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                window.showNotification("{{ session('success') }}", 'success');
            @endif

            @if(session('error'))
                window.showNotification("{{ session('error') }}", 'error');
            @endif

            @if(session('info'))
                window.showNotification("{{ session('info') }}", 'info');
            @endif
        });
    </script>
    @endpush
</x-app-layout> 