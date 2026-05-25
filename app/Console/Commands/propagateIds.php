<?php

namespace App\Console\Commands;

use App\Models\Athlete;
use Illuminate\Console\Command;

class propagateIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ids:propagate {year}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy the ids from the lastYearIdentNo field to the newIdentNo field';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $year = $this->argument('year');

        // Count all athletes for the given year
        $totalAthletes = Athlete::where('year', $year)->count();

        $this->info("Found {$totalAthletes} athletes for year {$year}.");

        // Find athletes eligible for update
        $query = Athlete::where('year', $year)
            ->whereNotNull('lastYearIdentNo')
            ->whereNull('newIdentNo');

        $toUpdateCount = $query->count();

        $this->info(
            "{$toUpdateCount} athletes have lastYearIdentNo set and newIdentNo empty."
        );

        if ($toUpdateCount === 0) {
            $this->info('Nothing to update.');

            return Command::SUCCESS;
        }

        // Ask for confirmation
        if (! $this->confirm('Do you want to copy lastYearIdentNo to newIdentNo for these athletes?')) {
            $this->warn('Operation cancelled.');

            return Command::SUCCESS;
        }

        // Perform updates
        $updated = 0;

        $query->chunkById(100, function ($athletes) use (&$updated) {
            foreach ($athletes as $athlete) {
                $athlete->newIdentNo = $athlete->lastYearIdentNo;
                $athlete->save();

                $updated++;
            }
        });

        $this->info("Successfully updated {$updated} athletes.");

        return Command::SUCCESS;
    }
}
