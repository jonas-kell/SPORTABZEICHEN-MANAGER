<?php

namespace App\Http\Resources;

use App\Providers\RequirementsServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AthletePrintoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $scores = $this->getMedalScores();

        $performanceFormattedEndurance = RequirementsServiceProvider::formatPerformance("endurance", $scores["endurance"]["discipline_name"], $scores["endurance"]["performance"], $scores["endurance"]["points"]);
        $performanceFormattedStrength = RequirementsServiceProvider::formatPerformance("strength", $scores["strength"]["discipline_name"], $scores["strength"]["performance"], $scores["strength"]["points"]);
        $performanceFormattedSpeed = RequirementsServiceProvider::formatPerformance("speed", $scores["speed"]["discipline_name"], $scores["speed"]["performance"], $scores["speed"]["points"]);
        $performanceFormattedCoordination = RequirementsServiceProvider::formatPerformance("coordination", $scores["coordination"]["discipline_name"], $scores["coordination"]["performance"], $scores["coordination"]["points"]);

        $personArray = [
            "# Nachname, Vorname" => $this->name,                                                                           # Name
            "# Geschlecht" => $this->gender == "male" ? "m" : "w",                                                          # Geschlecht
            "# TTMMJJ" => Carbon::parse($this->birthday)->format("d.m.y"),                                                  # Geburtstag
            "Alter #" => $this->sportabzeichen_age,                                                                         # Alter
            "# Ziffer Übung" => $performanceFormattedEndurance["number"],                                                   # Ausdauer Ziffer
            "# Verband" => $performanceFormattedEndurance["performance"],                                                   # Ausdauer Leistung
            "# Punkte" => $scores["endurance"]["points"],                                                                   # Ausdauer Punkte
            "#B Ziffer Übung" => $performanceFormattedStrength["number"],                                                   # Kraft Ziffer
            "#B Verband" => $performanceFormattedStrength["performance"],                                                   # Kraft Leistung
            "#B Punkte" => $scores["strength"]["points"],                                                                   # Kraft Punkte
            "#C Ziffer Übung" => $performanceFormattedSpeed["number"],                                                      # Schnelligkeit Ziffer
            "#C Verband" => $performanceFormattedSpeed["performance"],                                                      # Schnelligkeit Leistung
            "#C Punkte" => $scores["speed"]["points"],                                                                      # Schnelligkeit Punkte
            "#D Ziffer Übung" => $performanceFormattedCoordination["number"],                                               # Koordination Ziffer
            "#D Verband" => $performanceFormattedCoordination["performance"],                                               # Koordination Leistung
            "#D Punkte" => $scores["coordination"]["points"],                                                               # Koordination Punkte
            "#JJJJ" => $this->proofOfSwimming ?? "",                                                                        # Schwimmfertigkeit
            "# Gesamtpunkte" => $scores["endurance"]["points"] +
                $scores["strength"]["points"] +
                $scores["speed"]["points"] +
                $scores["coordination"]["points"],                                                                          # Gesamtpunkte
            "# bisherige Sportabzeichen" => $this->numberOfBadgesSoFar ?? "0",                                              # bisherige Sportabzeichen
            "Ankreuzen #" => $this->hasFinished() ? "On" : "Off",                                                           # bestelle Abzeichen
            '$JJJJ' => $this->lastBadgeYear ?? "",                                                                          # letzte Prüfung
            "# Ident-Nr" => $this->lastYearIdentNo ?? "",                                                                           # Ident Nr
        ];

        return $personArray;
    }
}
