<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\LabelController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use App\Mail\ReminderEmail;
use App\Models\Reminder;
use Carbon\Carbon;

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

Route::get('/test-email', function() {
    try {
        Mail::raw('Test email from SmarterASP', function($message) {
            $message->to('test@example.com')->subject('Mailtrap Test');
        });
        return "Email sent! Check Mailtrap.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/run-reminders/{secret_key}', function ($secret_key) {

    // 1. SECURITY CHECK
    if ($secret_key !== '2b2e1cd9f5e837e71c0390a6301fb8e1') {
        abort(403, 'Unauthorized action.');
    }

    // 2. THE FINAL TIMEZONE-CORRECTED LOGIC
    // We are now forcing the timezone to be your correct one: 'Asia/Damascus'
    $currentTime = Carbon::now('Asia/Damascus');
    Log::info("==============================================");
    Log::info("[CRON JOB] Starting check. Forced Timezone: Asia/Damascus. Current script time: " . $currentTime->toDateTimeString());

    $in24Hours = $currentTime->copy()->addHours(24);
    Log::info("[CRON JOB] Checking for reminders between now and: " . $in24Hours->toDateTimeString());

    $reminders = Reminder::where('reminder_at', '>', $currentTime)
        ->where('reminder_at', '<=', $in24Hours)
        ->where('email_sent', false)
        ->get();

    Log::info('[CRON JOB] Query finished. Found ' . $reminders->count() . ' reminders to process.');

    foreach ($reminders as $reminder) {
        $user = $reminder->user;
        if ($user && $user->email) {
            try {
                Mail::to($user->email)->send(new ReminderEmail($reminder));
                Log::info("[CRON JOB] Successfully sent email for reminder ID: {$reminder->id}");
                
                // --- DIAGNOSTIC CODE IS HERE ---
                $reminder->email_sent = true;
                if ($reminder->save()) {
                    Log::info("[CRON JOB] SUCCESS: email_sent status SAVED for reminder ID: {$reminder->id}");
                } else {
                    Log::error("[CRON JOB] FAILED to save email_sent status for reminder ID: {$reminder->id}. The save() method returned false.");
                }
                
            } catch (\Exception $e) {
                Log::error("[CRON JOB] Failed to send email. Error: " . $e->getMessage());
            }
        }
    }

    Log::info('[CRON JOB] Reminder check finished.');
    Log::info("==============================================");

    // 3. RETURN A DYNAMIC RESPONSE
    return "Reminder check completed at: " . now('Asia/Damascus')->toDateTimeString();
});

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






require __DIR__ . '/auth.php';
