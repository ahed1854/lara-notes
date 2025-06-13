<x-app-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Trash</h2>
                <div class="flex space-x-4">
                    @if($trashedReminders->count() > 0)
                        <form action="{{ route('reminders.force-delete-all') }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete all reminders in trash? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 flex items-center">
                                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete All
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('reminders.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to Reminders</a>
                </div>
            </div>

            <div class="space-y-4">
                @forelse ($trashedReminders as $reminder)
                    <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ $reminder->title }}</h3>
                            @if ($reminder->description)
                                <p class="mt-1 text-sm text-gray-600">{{ $reminder->description }}</p>
                            @endif
                            <div class="mt-2 flex items-center space-x-4">
                                <span class="flex items-center text-sm text-gray-500">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($reminder->reminder_at)->format('M d, Y h:i A') }}
                                </span>
                                <span class="text-sm text-gray-500">Deleted {{ $reminder->deleted_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <form action="{{ route('reminders.restore', $reminder->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-indigo-600 hover:text-indigo-900">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </button>
                            </form>
                            <form action="{{ route('reminders.force-delete', $reminder->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to permanently delete this reminder?')">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No reminders in trash</h3>
                        <p class="mt-1 text-sm text-gray-500">Deleted reminders will appear here.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout> 