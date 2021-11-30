<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use mikehaertl\pdftk\Pdf;
use Storage;

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
    protected $description = 'Generate the filled-out pdf to submit';

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
        $templatePDFLocation = resource_path("DSA_Gruppenpruefkarte_2021_beschreibbar.pdf");

        $targetPDFFolder = "generated_output_pdfs";
        Storage::makeDirectory($targetPDFFolder);
        $targetPDFName = "test.pdf";

        $targetPDFLocation = storage_path("app" . DIRECTORY_SEPARATOR . $targetPDFFolder . DIRECTORY_SEPARATOR . $targetPDFName);

        # "pdftk" command must be available!!! 
        # https://www.pdflabs.com/tools/pdftk-server/
        # https://github.com/mikehaertl/php-pdftk/issues/22#issuecomment-497229511
        $pdf = new Pdf($templatePDFLocation, config("pdftk.configuration_array"));

        $result = $pdf->fillForm([0 => "asdasd"])->needAppearances()->saveAs($targetPDFFolder);

        // Always check for errors
        if ($result === false) {
            $this->error($pdf->getError());

            return Command::FAILURE;
        }

        $this->info("Stored the pdf to: " . $targetPDFLocation);

        return Command::SUCCESS;
    }
}
