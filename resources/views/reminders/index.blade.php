<x-app-layout>
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <!-- Create Reminder Form -->
            <form id="createReminderForm" action="{{ route('reminders.store') }}" method="POST" class="mb-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <input type="text" name="title" placeholder="Reminder title" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    </div>
                    <div>
                        <input type="datetime-local" name="reminder_at" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    </div>
                </div>
                <div class="mt-4">
                    <textarea name="description" placeholder="Description (optional)" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"></textarea>
                </div>
                <div class="mt-4 flex justify-between items-center">
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
                    <button type="submit" class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                        Create Reminder
                    </button>
                </div>
            </form>

            <!-- Reminders List -->
            <div class="space-y-4">
                @forelse ($reminders as $reminder)
                    <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                        <div class="flex items-start space-x-4">
                            <form action="{{ route('reminders.toggle-complete', $reminder) }}" method="POST" onsubmit="setTimeout(() => window.showNotification('Reminder status updated', 'success'), 100)">
                                @csrf
                                <button type="submit" class="mt-1">
                                    @if ($reminder->is_completed)
                                        <svg class="h-5 w-5 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                        </svg>
                                    @endif
                                </button>
                            </form>
                            <div class="flex-1">
                                <h3 class="text-lg font-medium {{ $reminder->is_completed ? 'text-gray-500 dark:text-gray-400 line-through' : 'text-gray-900 dark:text-gray-100' }}">
                                    {{ $reminder->title }}
                                </h3>
                                @if ($reminder->description)
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $reminder->description }}</p>
                                @endif
                                <div class="mt-2 flex items-center space-x-4">
                                    <span class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($reminder->reminder_at)->format('M d, Y h:i A') }}
                                    </span>
                                    @if ($reminder->note)
                                        <span class="text-sm text-indigo-600 dark:text-indigo-400">Linked to note: {{ $reminder->note->title ?: 'Untitled' }}</span>
                                    @endif
                                    @if($reminder->labels->count() > 0)
                                        <div class="flex flex-wrap gap-1 mt-1">
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
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="editReminder({{ $reminder->id }})" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <form action="{{ route('reminders.destroy', $reminder) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to move this reminder to trash?')">
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
                @empty
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No reminders</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new reminder.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Edit Reminder Modal -->
    <div id="editReminderModal" class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 hidden" x-data="{ open: false }">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-8 max-w-lg w-full mx-4" @click.stop>
                <h2 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Edit Reminder</h2>
                <form id="editReminderForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <input type="text" name="title" id="editTitle" placeholder="Reminder title" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>
                        <div>
                            <input type="datetime-local" name="reminder_at" id="editReminderAt" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>
                        <div>
                            <textarea name="description" id="editDescription" placeholder="Description (optional)" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"></textarea>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_completed" id="editIsCompleted" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 dark:text-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Mark as completed</span>
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
                    </div>
                    <div class="mt-6 flex justify-end space-x-2">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600">
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
            // Show notification if status parameter exists
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            if (status === 'created') {
                window.showNotification('Reminder created successfully', 'success');
                // Clean up the URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }

            // Existing session message handling
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

            window.editReminder = function(reminderId) {
                console.log('Fetching reminder data for ID:', reminderId);
                fetch(`/api/reminders/${reminderId}`, {
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
                .then(reminder => {
                    console.log('Reminder data received:', reminder);
                    document.getElementById('editTitle').value = reminder.title || '';
                    document.getElementById('editDescription').value = reminder.description || '';
                    document.getElementById('editReminderAt').value = reminder.reminder_at ? reminder.reminder_at.slice(0, 16) : '';
                    document.getElementById('editIsCompleted').checked = reminder.is_completed;

                    // Update label checkboxes
                    const labelIds = reminder.labels.map(label => label.id);
                    document.querySelectorAll('.editLabelCheckbox').forEach(checkbox => {
                        checkbox.checked = labelIds.includes(parseInt(checkbox.value));
                    });

                    document.getElementById('editReminderForm').action = `/reminders/${reminderId}`;
                    document.getElementById('editReminderModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.showNotification('Error loading reminder data. Please try again.', 'error');
                });
            };

            window.closeEditModal = function() {
                document.getElementById('editReminderModal').classList.add('hidden');
            };

            // Close modal when clicking outside
            document.getElementById('editReminderModal').addEventListener('click', function(event) {
                if (event.target === this) {
                    closeEditModal();
                }
            });

            // Handle form submission
            document.getElementById('editReminderForm').addEventListener('submit', function(event) {
                event.preventDefault();
                const form = this;
                
                // Gather form data
                const formData = {
                    title: form.querySelector('#editTitle').value,
                    description: form.querySelector('#editDescription').value,
                    reminder_at: form.querySelector('#editReminderAt').value,
                    is_completed: form.querySelector('#editIsCompleted').checked,
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
                        throw new Error(data.message || 'Error updating reminder');
                    }
                    return data;
                })
                .then(data => {
                    console.log('Response:', data);
                    closeEditModal();
                    window.showNotification(data.message || 'Reminder updated successfully', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.showNotification(error.message || 'Error updating reminder. Please try again.', 'error');
                });
            });

            // Handle create form submission
            document.getElementById('createReminderForm').addEventListener('submit', function(event) {
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
                        throw new Error(data.message || 'Error creating reminder');
                    }
                    return data;
                })
                .then(data => {
                    form.reset();
                    window.location.href = window.location.pathname + '?status=created';
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.showNotification(error.message || 'Error creating reminder. Please try again.', 'error');
                });
            });
        });
    </script>
    @endpush
</x-app-layout> 