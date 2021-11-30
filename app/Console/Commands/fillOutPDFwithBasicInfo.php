<?php

namespace App\Console\Commands;

use Carbon\Carbon;
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

        $result = $pdf->fillForm(self::formFillingValues())->needAppearances()->saveAs($targetPDFLocation);

        // Always check for errors
        if ($result === false) {
            $this->error($pdf->getError());

            return Command::FAILURE;
        }

        $this->info("Stored the pdf to: " . $targetPDFLocation);

        return Command::SUCCESS;
    }

    private static function formFillingValues(): array
    {
        $arr = [
            "Verein" => "On",
            "Name" => "TSV Schwabmünchen Leichtathletik",  //TODO dynamic
            "Ort/Land" => "86830 Schwabmünchen / Bayern", //TODO dynamic
            "Jahr der Prüfung" => "21", //TODO dynamic
            "Name Prüfer" => "Prüfer", //TODO dynamic
            "Ident-Nr" => "Ident nr Prüfer", //TODO dynamic
            "Tel. Nummer" => "Tel nr Prüfer", //TODO dynamic
            "Ort, Datum" => Carbon::now()->format("d.m.Y"), //TODO dynamic
            "E-Mail" => "E-Mail Prüfer", //TODO dynamic
        ];

        $personArray = [
            "# Nachname, Vorname" => "Name",
            "# Geschlecht" => "m",      #Geschlecht
            "Alter #" => "23",          #Alter
            "# TTMMJJ" => "31.12.00",   #Geburtstag
            "# Ziffer Übung" => "1",    #Ausdauer Ziffer
            "# Verband" => "2",         #Ausdauer Leistung
            "# Punkte" => "3",          #Ausdauer Punkte
            "#B Ziffer Übung" => "4",   #Kraft Ziffer
            "#B Verband" => "5",        #Kraft Leistung
            "#B Punkte" => "6",         #Kraft Punkte
            "#C Ziffer Übung" => "7",   #Schnelligkeit Ziffer
            "#C Verband" => "8",        #Schnelligkeit Leistung
            "#C Punkte" => "9",         #Schnelligkeit Punkte
            "#D Ziffer Übung" => "a",   #Koordination Ziffer
            "#D Verband" => "b",        #Koordination Leistung
            "#D Punkte" => "c",         #Koordination Punkte
            "#JJJJ" => "2020",          #Schwimmfertigkeit
            // "# Gesamtpunkte" => "9",    #Gesamtpunkte -> werden automatisch ausgefüllt
            "# bisherige Sportabzeichen" => "1",    #bisherige Sportabzeichen
            "Ankreuzen #" => "On",      #bestelle Abzeichen
            '$JJJJ' => "asd",           #letzte Prüfung
            "# Ident-Nr" => "Nr",       #Ident Nr
        ];

        for ($i = 1; $i <= 10; $i++) {
            foreach ($personArray as $key => $value) {
                $value = $value == "On" ? $value : $i . $value;

                // make sure to hit all fields even if they are miss-named (thx a lot...)
                $arr[str_replace("#", $i, $key)] = $value;
                $arr[str_replace("$", chr(ord("A") - 1 + $i < ord("J") ? ord("A") - 1 + $i : ord("A") + $i), $key)] = $value;

                // extra case... : 
                if ($i == 1 && $key == "# Geschlecht") {
                    unset($arr["1 Geschlecht"]);
                    $arr["Geschlecht"] = $value;
                }
            }
        }

        return $arr;
    }
}
