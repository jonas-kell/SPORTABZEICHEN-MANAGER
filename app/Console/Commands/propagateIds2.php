<?php

namespace App\Console\Commands;

use App\Models\Athlete;
use Illuminate\Console\Command;

class propagateIds2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ids:propagate-ac-year {year} {year2}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy the ids from the lastYearIdentNo field to the newIdentNo field accross a year boundary';

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
        $year2 = $this->argument('year2');

        // Count all athletes for the given year
        $totalAthletesFrom = Athlete::where('year', $year)->count();
        $totalAthletesTo = Athlete::where('year', $year2)->count();

        $this->info("Found {$totalAthletesFrom} athletes for year {$year} and {$totalAthletesTo} athletes for year {$year2}");

        // Find athletes eligible for update
        $query = Athlete::where('year', $year2)
            ->whereNull('lastYearIdentNo')
            ->whereNull('newIdentNo');

        $toUpdateCount = $query->count();

        $this->info(
            "{$toUpdateCount} athletes have lastYearIdentNo and newIdentNo empty in {$year2}"
        );

        if ($toUpdateCount === 0) {
            $this->info('Nothing to update.');

            return Command::SUCCESS;
        }

        // Ask for confirmation
        if (! $this->confirm("Do you want to copy newIdentNo from {$year} to lastYearIdentNo and newIdentNo in {$year2} for these athletes?")) {
            $this->warn('Operation cancelled.');

            return Command::SUCCESS;
        }

        // Perform updates
        $updated = 0;

        $query->chunkById(100, function ($athletes) use (&$updated) {
            foreach ($athletes as $athlete) {
                $athleteFromYear = Athlete::where('externalId', $athlete->externalId)->first();

                if ($athleteFromYear != null && $athleteFromYear->newIdentNo != null) {
                    $athlete->newIdentNo = $athleteFromYear->newIdentNo;
                    $athlete->lastYearIdentNo = $athleteFromYear->newIdentNo;
                    $athlete->save();
                }

                $updated++;
            }
        });

        $this->info("Successfully updated {$updated} athletes.");

        return Command::SUCCESS;
    }
}
