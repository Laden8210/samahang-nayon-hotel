<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@yield('title', 'Your App Name')</title>
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.css') }}">
    @livewireStyles

</head>

<body>
    @yield('content')
</body>
@livewireScripts
<script src="{{ asset('js/app.js') }}"></script>

</html>
