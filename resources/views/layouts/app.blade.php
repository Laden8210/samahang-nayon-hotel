<!doctype html>
<html>

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@yield('title', 'Your App Name')</title>
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.css') }}">
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="
        https://cdn.jsdelivr.net/npm/animate-js@0.3.2/src/index.min.js
        "></script>

</head>

<body>

    <aside class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar">

        <x-sidebar />
    </aside>

    <!-- Main Content Area -->
    <div class="sm:ml-64">
        <!-- Header Bar -->
        <div>
            <x-header-bar class="shadow-lg" id="mainHeader" />
        </div>

        <main class="p-2 flex-grow ">
            <div class=" mx-auto z-10">
                @yield('content')
            </div>
        </main>
    </div>

</body>
@livewireScripts
<script src="{{ asset('js/app.js') }}"></script>

</html>
