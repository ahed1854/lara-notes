<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reminder; // Make sure to import your Reminder model
use App\Models\User;    // Import the User model
use Illuminate\Support\Facades\Mail; // Import the Mail facade
use App\Mail\ReminderEmail; // We'll create this Mail class soon
use Carbon\Carbon; // For working with dates and times
use Illuminate\Support\Facades\Log;


class SendReminderEmails extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for reminders due in 24 hours and send emails';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        Log::info('SendReminderEmails command started.');
        $this->info('Checking for reminders due soon and not yet notified...');

        // Get the current time and the time 24 hours from now
        $now = Carbon::now();
        $in24Hours = Carbon::now()->addHours(24);

        // getting reminders within 24 hours that hasn't been notified
        $reminders = Reminder::where('reminder_at', '>', $now)
            ->where('reminder_at', '<=', $in24Hours)
            ->where('email_sent', false)
            ->get();

        if ($reminders->isEmpty()) {
            $this->info('No reminders due in the next 24 hours.');
            Log::info('No reminders due in the next 24 hours to send.');
            return Command::SUCCESS;
        }

        $this->info(sprintf('Found %d reminder(s) to notify.', $reminders->count()));

        foreach ($reminders as $reminder) {
            $user = $reminder->user;

            if ($user && $user->email) {
                try {
                    Mail::to($user->email)->send(new ReminderEmail($reminder));
                    $this->info("Reminder email sent to: {$user->email} for reminder: {$reminder->title}");
                    Log::info("Reminder email sent to: {$user->email} for reminder: {$reminder->title}");

                    // Mark the reminder as email_sent
                    $reminder->email_sent = true;
                    $reminder->save();
                    Log::info("Marked reminder ID: {$reminder->id} as email_sent.");
                    $this->info("Marked reminder ID: {$reminder->id} as email_sent.");
                } catch (\Exception $e) {
                    $this->error("Failed to send email to: {$user->email} for reminder ID: {$reminder->id}. Error: " . $e->getMessage());
                    Log::error("Failed to send email to: {$user->email} for reminder ID: {$reminder->id}. Error: " . $e->getMessage());
                }
            } else {
                $this->warn("User or user email not found for reminder ID: {$reminder->id}");
            }
        }

        $this->info('Finished sending reminder emails.');
        return Command::SUCCESS;
    }
}
