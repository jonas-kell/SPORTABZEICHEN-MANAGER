<?php

namespace App\Providers;

use App\Http\Resources\AthletePrintoutResource;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Console\Command;
use mikehaertl\pdftk\Pdf;
use Storage;
use Str;

class PdfGenerationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    //generate a pdf for a set of athletes
    public static function generateOutputPdf($athletes, &$pdf, &$error)
    {
        $templatePDFLocation = resource_path("DSA_Gruppenpruefkarte_2021_beschreibbar.pdf");

        $targetPDFFolder = "generated_output_pdfs";
        Storage::makeDirectory($targetPDFFolder);
        // $targetPDFName = "test.pdf"; # debug switch
        $targetPDFName = Str::uuid() . ".pdf";

        $targetPdfLocationInStorage = $targetPDFFolder . DIRECTORY_SEPARATOR . $targetPDFName;
        $targetPDFLocation = storage_path("app" . DIRECTORY_SEPARATOR . $targetPdfLocationInStorage);

        # "pdftk" command must be available!!! 
        # https://www.pdflabs.com/tools/pdftk-server/
        # https://github.com/mikehaertl/php-pdftk/issues/22#issuecomment-497229511
        $pdf = new Pdf($templatePDFLocation, config("pdftk.configuration_array"));

        $result = $pdf->fillForm(self::formFillingValues($athletes))->needAppearances()->saveAs($targetPDFLocation);

        // Always check for errors
        if ($result === false) {
            $error = $pdf->getError();
            return Command::FAILURE;
        }

        $pdf = Storage::get($targetPdfLocationInStorage);

        return Command::SUCCESS;
    }

    private static function formFillingValues($athletes): array
    {
        $arr = [
            "Verein" => "On",
            "Name" => config("pdf_printout.association_name"),
            "Ort/Land" => config("pdf_printout.location"),
            "Jahr der Prüfung" => Carbon::now()->format("y"),
            "Name Prüfer" => config("pdf_printout.validator_name"),
            "Ident-Nr" => config("pdf_printout.validator_ident_nr"),
            "Tel. Nummer" => config("pdf_printout.validator_tel_nr"),
            "Ort, Datum" => config("pdf_printout.validated_location") . ", " . Carbon::now()->format("d.m.Y"),
            "E-Mail" => config("pdf_printout.validator_email"),
        ];

        $i = 1;
        foreach ($athletes as $athlete) {

            // insert values into fill-in-array
            foreach ((new AthletePrintoutResource($athlete))->toArray(null) as $key => $value) {
                // make sure to hit all fields even if they are miss-named (thx a lot...)
                $arr[str_replace("#", $i, $key)] = $value;
                $arr[str_replace("$", chr(ord("A") - 1 + $i < ord("J") ? ord("A") - 1 + $i : ord("A") + $i), $key)] = $value;

                // extra case... : 
                if ($i == 1 && $key == "# Geschlecht") {
                    unset($arr["1 Geschlecht"]);
                    $arr["Geschlecht"] = $value;
                }
            }

            $i++;
            if ($i > 10) {
                break;
            }
        }

        return $arr;
    }
}
