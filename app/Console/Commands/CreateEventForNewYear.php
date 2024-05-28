<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\event;
use Carbon\Carbon;

class CreateEventForNewYear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:create-new-year';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create event for the new year if not already exists';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currentYear = Carbon::now()->year;

        // Check if an event for the current year already exists
        $eventExists = event::where('tahun_acara', $currentYear)->exists();

        if (!$eventExists) {
            // Create the event for the new year
            event::create([
                'total_kepala_keluarga' => 0, // Initial value, can be updated later
                'tahun_acara' => $currentYear,
                'total_pendapatan' => 0.00, // Initial value, can be updated later
            ]);

            $this->info("Event for year $currentYear created successfully.");
        } else {
            $this->info("Event for year $currentYear already exists.");
        }

        return 0;
    }
}
