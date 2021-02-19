<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{!! asset('svg/logo.svg') !!}"/>

    <!-- Scripts -->
    <script src="{{ mix('js/manifest.js') }}" Cache-Control: no-cache, must-revalidate defer></script>
  <script src="{{ mix('js/vendor.js') }}" Cache-Control: no-cache, must-revalidate defer></script>
  <script src="{{ mix('js/app.js') }}" Cache-Control: no-cache, must-revalidate defer></script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons" rel="stylesheet">
    {{-- <link href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" rel="stylesheet"> --}}
    <script>
        @auth
          window.Permissions = {!! json_encode(Auth::user()->allPermissions, true) !!};
        @else
          window.Permissions = [];
        @endauth
      </script>


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
            @yield('content')
    </div>
</body>
</html>
