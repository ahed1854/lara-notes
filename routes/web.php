<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\LabelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $notes = auth()->user()->notes()
            ->with('labels')
            ->latest()
            ->take(3)
            ->get();
        
        $reminders = auth()->user()->reminders()
            ->with('labels')
            ->where('reminder_at', '>=', now())
            ->where('is_completed', false)
            ->orderBy('reminder_at')
            ->take(3)
            ->get();
            
        return view('dashboard', compact('notes', 'reminders'));
    })->name('dashboard');

    // API routes for editing
    Route::get('/api/notes/{note}', [NoteController::class, 'show'])->name('api.notes.show');
    Route::get('/api/reminders/{reminder}', [ReminderController::class, 'show'])->name('api.reminders.show');

    // Notes routes - static routes first, then dynamic routes
    Route::get('/notes/trash', [NoteController::class, 'trash'])->name('notes.trash');
    Route::delete('/notes/force-delete-all', [NoteController::class, 'forceDeleteAll'])->name('notes.force-delete-all');
    Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::put('/notes/{note}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');
    Route::post('/notes/{id}/restore', [NoteController::class, 'restore'])->name('notes.restore');
    Route::delete('/notes/{id}/force-delete', [NoteController::class, 'forceDelete'])->name('notes.force-delete');

    // Reminders routes - static routes first, then dynamic routes
    Route::get('/reminders/trash', [ReminderController::class, 'trash'])->name('reminders.trash');
    Route::delete('/reminders/force-delete-all', [ReminderController::class, 'forceDeleteAll'])->name('reminders.force-delete-all');
    Route::get('/reminders', [ReminderController::class, 'index'])->name('reminders.index');
    Route::post('/reminders', [ReminderController::class, 'store'])->name('reminders.store');
    Route::put('/reminders/{reminder}', [ReminderController::class, 'update'])->name('reminders.update');
    Route::delete('/reminders/{reminder}', [ReminderController::class, 'destroy'])->name('reminders.destroy');
    Route::post('/reminders/{id}/restore', [ReminderController::class, 'restore'])->name('reminders.restore');
    Route::delete('/reminders/{id}/force-delete', [ReminderController::class, 'forceDelete'])->name('reminders.force-delete');
    Route::post('/reminders/{reminder}/toggle-complete', [ReminderController::class, 'toggleComplete'])->name('reminders.toggle-complete');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Trash routes
    Route::get('/trash', [TrashController::class, 'index'])->name('trash.index');
    Route::post('/trash/notes/{id}/restore', [TrashController::class, 'restoreNote'])->name('trash.restore-note');
    Route::post('/trash/reminders/{id}/restore', [TrashController::class, 'restoreReminder'])->name('trash.restore-reminder');
    Route::delete('/trash/notes/{id}', [TrashController::class, 'destroyNote'])->name('trash.destroy-note');
    Route::delete('/trash/reminders/{id}', [TrashController::class, 'destroyReminder'])->name('trash.destroy-reminder');
    Route::delete('/trash/notes', [TrashController::class, 'destroyAllNotes'])->name('trash.destroy-all-notes');
    Route::delete('/trash/reminders', [TrashController::class, 'destroyAllReminders'])->name('trash.destroy-all-reminders');

    // Labels routes
    Route::resource('labels', LabelController::class);
    Route::get('/api/labels/{label}', [LabelController::class, 'show']);
});

require __DIR__.'/auth.php';
