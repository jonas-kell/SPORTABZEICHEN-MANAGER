<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @include("layouts.inc.navbar")

        <main class="py-4">
            <div class="row justify-content-center no-gutters">
                <div class="col-md-4 col-12">
                    <div class="row justify-content-center mr-3 ml-3 mb-2">
                        @yield('side')
                    </div>
                </div>
                <div class="col-md-8 col-12">
                    <div class="row justify-content-center mr-3 ml-3">
                        @yield('center')
                    </div>
                </div>
            </div>
        </main>
        <main class="py-4 row justify-content-center ">
            @yield('content')
        </main>
    </div>
    @include('cookieConsent::index')
</body>

<!-- Scripts -->
<script>
    window._locale = '{{ app()->getLocale() }}';
    window._translations = {!! cache('translations') !!};
</script>
<script src="{{ mix('/js/app.js') }}"></script>
</html>
