<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl font-bold mb-6">Manage Labels</h2>

                    <!-- Create Label Form -->
                    <form action="{{ route('labels.store') }}" method="POST" class="mb-8">
                        @csrf
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1">
                                <label for="name" class="block text-sm font-medium mb-2">Label Name</label>
                                <input type="text" name="name" id="name" placeholder="Enter label name" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md 
                                           focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 
                                           bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            </div>
                            <div class="w-full md:w-48">
                                <label for="color" class="block text-sm font-medium mb-2">Color</label>
                                <input type="color" name="color" id="color" required
                                    class="h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 
                                           bg-white dark:bg-gray-700 cursor-pointer">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" 
                                    class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-md 
                                           hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none 
                                           focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                    Add Label
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Labels List -->
                    <div class="space-y-4">
                        @forelse ($labels as $label)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-6 h-6 rounded" style="background-color: {{ $label->color }}"></div>
                                    <span class="text-lg">{{ $label->name }}</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">({{ $label->notes->count() + $label->reminders->count() }} items)</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button onclick="editLabel({{ $label->id }})" 
                                        class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <form action="{{ route('labels.destroy', $label) }}" method="POST" class="inline" 
                                        onsubmit="return confirm('Are you sure you want to delete this label?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No labels</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new label.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Label Modal -->
    <div id="editLabelModal" class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-8 max-w-lg w-full mx-4">
                <h2 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Edit Label</h2>
                <form id="editLabelForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label for="editName" class="block text-sm font-medium mb-2">Label Name</label>
                            <input type="text" name="name" id="editName" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md 
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 
                                       bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>
                        <div>
                            <label for="editColor" class="block text-sm font-medium mb-2">Color</label>
                            <input type="color" name="color" id="editColor" required
                                class="h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 
                                       bg-white dark:bg-gray-700 cursor-pointer">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-2">
                        <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 
                                   rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-md 
                                   hover:bg-indigo-700 dark:hover:bg-indigo-600">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            window.editLabel = function(labelId) {
                fetch(`/api/labels/${labelId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(label => {
                    document.getElementById('editName').value = label.name;
                    document.getElementById('editColor').value = label.color;
                    document.getElementById('editLabelForm').action = `/labels/${labelId}`;
                    document.getElementById('editLabelModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.showNotification('Error loading label data. Please try again.', 'error');
                });
            };

            window.closeEditModal = function() {
                document.getElementById('editLabelModal').classList.add('hidden');
            };

            // Close modal when clicking outside
            document.getElementById('editLabelModal').addEventListener('click', function(event) {
                if (event.target === this) {
                    closeEditModal();
                }
            });

            // Show session messages
            @if(session('success'))
                window.showNotification("{{ session('success') }}", 'success');
            @endif

            @if(session('error'))
                window.showNotification("{{ session('error') }}", 'error');
            @endif
        });
    </script>
    @endpush
</x-app-layout> 