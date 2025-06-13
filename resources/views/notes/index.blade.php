<x-app-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <!-- Create Note Form -->
            <form id="createNoteForm" action="{{ route('notes.store') }}" method="POST" class="mb-8">
                @csrf
                <div class="mb-4">
                    <input type="text" name="title" placeholder="Title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>
                <div class="mb-4">
                    <textarea name="content" placeholder="Take a note..." rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"></textarea>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div>
                            <input type="hidden" name="is_pinned" value="0">
                            <input type="checkbox" name="is_pinned" value="1" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 dark:text-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Pin note</span>
                        </div>
                        @if($labels->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($labels as $label)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="labels[]" value="{{ $label->id }}" 
                                            class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 dark:text-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 flex items-center">
                                            <span class="w-3 h-3 rounded-full mr-1" style="background-color: {{ $label->color }}"></span>
                                            {{ $label->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                        Create Note
                    </button>
                </div>
            </form>

            <!-- Notes Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($notes as $note)
                    <div class="relative p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-200 bg-white dark:bg-gray-700">
                        @if ($note->is_pinned)
                            <svg class="absolute top-2 right-2 h-5 w-5 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.828.722a.5.5 0 01.354 0l7.071 7.071a.5.5 0 010 .707l-7.071 7.071a.5.5 0 01-.707 0L2.404 8.5a.5.5 0 010-.707L9.475.722a.5.5 0 01.353 0z"/>
                            </svg>
                        @endif
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $note->title ?: 'Untitled' }}</h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-300">{{ $note->content }}</p>
                            @if($note->labels->count() > 0)
                                <div class="flex flex-wrap gap-1 mt-2">
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
                        <div class="flex items-center justify-between mt-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $note->created_at->diffForHumans() }}</span>
                            <div class="flex space-x-2">
                                <button onclick="editNote({{ $note->id }})" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <form action="{{ route('notes.destroy', $note) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to move this note to trash?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No notes</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new note.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Edit Note Modal -->
    <div id="editNoteModal" class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 hidden" x-data="{ open: false }">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-8 max-w-lg w-full mx-4" @click.stop>
                <h2 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Edit Note</h2>
                <form id="editNoteForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <input type="text" name="title" id="editTitle" placeholder="Title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    </div>
                    <div class="mb-4">
                        <textarea name="content" id="editContent" placeholder="Take a note..." rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"></textarea>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <input type="hidden" name="is_pinned" value="0">
                            <input type="checkbox" name="is_pinned" id="editIsPinned" value="1" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 dark:text-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Pin note</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($labels as $label)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="labels[]" value="{{ $label->id }}" 
                                        class="editLabelCheckbox rounded border-gray-300 dark:border-gray-600 text-indigo-600 dark:text-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 flex items-center">
                                        <span class="w-3 h-3 rounded-full mr-1" style="background-color: {{ $label->color }}"></span>
                                        {{ $label->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <div class="flex space-x-2">
                            <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show notification if status parameter exists
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            if (status === 'created') {
                window.showNotification('Note created successfully', 'success');
                // Clean up the URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }

            // Show session messages
            @if(session('success'))
                window.showNotification("{{ session('success') }}", 'success');
            @endif

            @if(session('error'))
                window.showNotification("{{ session('error') }}", 'error');
            @endif

            @if(session('info'))
                window.showNotification("{{ session('info') }}", 'info');
            @endif

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            window.editNote = function(noteId) {
                console.log('Fetching note data for ID:', noteId);
                fetch(`/api/notes/${noteId}`, {
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
                .then(note => {
                    console.log('Note data received:', note);
                    document.getElementById('editTitle').value = note.title || '';
                    document.getElementById('editContent').value = note.content;
                    document.getElementById('editIsPinned').checked = note.is_pinned;

                    // Update label checkboxes
                    const labelIds = note.labels.map(label => label.id);
                    document.querySelectorAll('.editLabelCheckbox').forEach(checkbox => {
                        checkbox.checked = labelIds.includes(parseInt(checkbox.value));
                    });

                    document.getElementById('editNoteForm').action = `/notes/${noteId}`;
                    document.getElementById('editNoteModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.showNotification('Error loading note data. Please try again.', 'error');
                });
            };

            window.closeEditModal = function() {
                document.getElementById('editNoteModal').classList.add('hidden');
            };

            // Close modal when clicking outside
            document.getElementById('editNoteModal').addEventListener('click', function(event) {
                if (event.target === this) {
                    closeEditModal();
                }
            });

            // Handle form submission
            document.getElementById('editNoteForm').addEventListener('submit', function(event) {
                event.preventDefault();
                const form = this;
                
                // Gather form data
                const formData = {
                    title: form.querySelector('#editTitle').value,
                    content: form.querySelector('#editContent').value,
                    is_pinned: form.querySelector('#editIsPinned').checked,
                    labels: Array.from(form.querySelectorAll('.editLabelCheckbox:checked')).map(cb => cb.value),
                    _method: 'PUT'
                };

                console.log('Submitting form to:', form.action);
                fetch(form.action, {
                    method: 'POST',
                    body: JSON.stringify(formData),
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Error updating note');
                    }
                    return data;
                })
                .then(data => {
                    console.log('Response:', data);
                    closeEditModal();
                    window.showNotification(data.message || 'Note updated successfully', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.showNotification(error.message || 'Error updating note. Please try again.', 'error');
                });
            });

            // Handle create form submission
            document.getElementById('createNoteForm').addEventListener('submit', function(event) {
                event.preventDefault();
                const form = this;
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Error creating note');
                    }
                    return data;
                })
                .then(data => {
                    form.reset();
                    window.location.href = window.location.pathname + '?status=created';
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.showNotification(error.message || 'Error creating note. Please try again.', 'error');
                });
            });
        });
    </script>
    @endpush
</x-app-layout> 