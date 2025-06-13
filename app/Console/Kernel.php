<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    // protected function schedule(Schedule $schedule): void {
    //     // $schedule->command('inspire')->hourly();
    //     $schedule->command('reminders:send-daily') // This is the 'signature' from your command
    //         ->everyMinute(); // For testing, run it every minute. Later, you can change it.
    // }

    protected function schedule(Schedule $schedule) {
        // Test log to see if the scheduler itself is being run
        $schedule->call(function () {
            Log::info('Laravel Scheduler ran at: ' . now());
        })->everyMinute()->name('scheduler_test_log'); // Give it a name for clarity if needed

        // Your actual reminder command
        $schedule->command('reminders:send-daily')->everyMinute(); // Or your desired frequency
        // If you want to test this every minute temporarily:
        // $schedule->command('reminders:send-daily')->everyMinute();

        // The echo to a custom log file might not work due to permissions or pathing on InfinityFree
        // $schedule->exec('echo "Cron ran at $(date)" >> storage/logs/cron.log'); // Comment this out or remove for now
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands() {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
