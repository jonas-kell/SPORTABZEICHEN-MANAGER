@extends('layouts.app')

@section('content')
    <h1 class="text-center mb-5 pl-5 pr-5">TSV-SMÜ Sportabzeichen Manager</h1>
    <p class="col-12 text-center  pl-5 pr-5">
        Das Interne Tool zur Verwaltung von Sportabzeichen-Leistungen.
    </p>
    <p class="col-12 text-center  pl-5 pr-5">
        Bitte Einloggen!
    </p>
    <p class="col-12 text-center  pl-5 pr-5">
        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
    </p>
@endsection