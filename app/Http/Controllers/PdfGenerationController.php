<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Providers\PdfGenerationServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class PdfGenerationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * require rendering of html (possibly unsecure, I don't know how good the escaping on the pdf engine is)
     * 
     * @return pdf
     */
    public function generateTable(Request $request)
    {
        if ($request->ajax()) {
            $html = $request->input("htmlString");

            //TODO styles not dynamic...
            $extra_styles = "<style>
                            table.smoll-in-printout {
                                font-size: 90%;
                            }
                            table {
                                border-collapse: collapse;
                                width: 100%;
                                margin-bottom: 1rem;
                                color: #212529;
                            }
                            td, th {
                                border: 1px solid rgba(41, 41, 41);
                                padding: 2px;
                                font-size: 80%;
                            }
                            td {
                                padding-left: 0.5rem;
                            }
                            .gold {
                                color: green;
                                font-weight: bold;
                            }
                            .silver {
                                color: orange;
                                font-weight: bold;
                            }
                            .bronze {
                                color: red;
                                font-weight: bold;
                            }
                            .edges {
                                border-radius: 0.7em;
                                padding-left: 0.2em;
                                padding-right: 0.2em;
                            }
                            .printout-display-none {
                                display: none;
                            }
                            .wrapper-page {
                                page-break-after: always;
                            }
                            .wrapper-page:last-child {
                                page-break-after: avoid;
                            }
                            .verticalTableHeader p {
                                transform: rotate(270deg);
                                font-size: 70%;
                                margin-top: 7em;
                                margin-bottom: 7em;
                            }
                            td.highlighted {
                                background-color: #33af229a;
                            }
                        </style>";

            $pdf = \App::make('dompdf.wrapper')->setPaper('a4', 'landscape');;
            $pdf->loadHTML($extra_styles . $html);
            return $pdf->stream();
        } else {
            return redirect("/");
        }
    }

    /**
     * @return pdf
     */
    public function generateOutput(Request $request)
    {
        if ($request->ajax()) {
            $athleteIds = $request->input("athlete_ids");

            $athletes = Athlete::whereIn("id", $athleteIds)->get()->sortBy(function ($athlete, $key) use ($athleteIds) {
                return array_search($athlete->id, $athleteIds);
            });

            $pdf = null;
            $error = "";

            $status = PdfGenerationServiceProvider::generateBatchOfPdfs($athletes, $pdf, $error);

            if ($status == Command::SUCCESS) {
                return $pdf;
            } else {
                return abort(500, $error);
            }
        } else {
            return redirect("/");
        }
    }
}
