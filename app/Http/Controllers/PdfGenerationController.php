<?php

namespace App\Http\Controllers;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function generate(Request $request)
    {
        if ($request->ajax()) {
            $html = $request->input("htmlString");

            //TODO styles not dynamic...
            $extra_styles = "<style>
                            .table {
                                width: 100%;
                                margin-bottom: 1rem;
                                color: #212529;
                            }
                            table {
                                border-collapse: collapse;
                            }
                            td, th {
                                border: 1px solid rgba(41, 41, 41);
                                padding: 2px;
                                font-size: 80%;
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
                        </style>";

            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($extra_styles . $html);
            return $pdf->stream();
        } else {
            return redirect("/");
        }
    }
}
