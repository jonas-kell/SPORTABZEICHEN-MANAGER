<?php

namespace App\Console\Commands;

use App\Models\Athlete;
use App\Providers\PdfGenerationServiceProvider;
use Illuminate\Console\Command;

class fillOutPDFwithBasicInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdf:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the filled-out pdf to submit - Test version that uses just the first 10 athletes available';

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
        $pdf = null;
        $error = "";

        $status = PdfGenerationServiceProvider::generateOutputPdf(Athlete::all(), $pdf, $error);

        if ($status != Command::SUCCESS) {
            $this->error($error);

            return Command::FAILURE;
        } else {
            return Command::SUCCESS;
        }
    }
}
