<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Samahang Nayon - Hotel Reservation')</title>

    <!-- CSS and JavaScript imports -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/animate-js@0.3.2/src/index.min.js"></script>
    @livewireStyles
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <!-- Header / Hero Section -->
    <header class="bg-gradient-to-r from-green-600 to-green-400 p-6 text-white">
        <div class="container mx-auto text-center">
            <h1 class="text-4xl font-bold mb-2">Samahang Nayon</h1>
            <p class="text-xl">Experience tranquility and comfort. Book your stay with us now!</p>

        </div>
    </header>

    <!-- About Section -->
    <section class="container mx-auto my-10 px-5 text-center">
        <h2 class="text-3xl font-bold mb-4">About Samahang Nayon</h2>
        <p class="text-lg text-gray-700 mb-8">
            Nestled in the heart of nature, Samahang Nayon offers an escape from the everyday hustle. Our hotel
            combines luxury with tranquility, making it the perfect place to relax, recharge, and rejuvenate. With
            breathtaking views, top-notch facilities, and exceptional service, we ensure that your stay is comfortable
            and memorable.
        </p>
        <img src="https://scontent.fmnl13-1.fna.fbcdn.net/v/t39.30808-6/251812038_403610324789782_3574069820819899217_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=cc71e4&_nc_eui2=AeHLdnur_m8EX3IfXhxJGNA9TVhfm02YQtxNWF-bTZhC3NY8-doMc-6VgEO3Xd805Xcu4YnozWfCtwsEy-CgG9h-&_nc_ohc=W572H5A21TYQ7kNvgHvvBqI&_nc_zt=23&_nc_ht=scontent.fmnl13-1.fna&_nc_gid=AD768U296ghLfL3RFFt1k7L&oh=00_AYC6cxhSVjXInRohvfhb1Bc8Obf_BJ9wqPs_0-YbEEIgjQ&oe=6735247C"
            alt="Beautiful view of Samahang Nayon hotel" class="rounded-lg shadow-lg mx-auto">
    </section>

    <!-- Features Section -->
    <main class="container mx-auto my-10 px-5">
        <section id="features" class="text-center mb-10">
            <h2 class="text-3xl font-bold mb-6">Why Choose Samahang Nayon?</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <i class="fas fa-calendar-alt text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold">Easy Booking</h3>
                    <p class="text-gray-600">Reserve rooms effortlessly with our user-friendly app interface.</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <i class="fas fa-lock text-4xl text-green-500 mb-4"></i>
                    <h3 class="text-xl font-semibold">Secure Payments</h3>
                    <p class="text-gray-600">Enjoy secure transactions with various payment options.</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <i class="fas fa-headset text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold">24/7 Support</h3>
                    <p class="text-gray-600">We’re here around the clock to help with your booking needs.</p>
                </div>
            </div>
        </section>

        <!-- Download Section -->
        <section id="download"
            class="text-center bg-gradient-to-r from-green-500 to-green-600 p-10 text-white rounded-lg shadow-lg">
            <h2 class="text-3xl font-bold mb-6">Book Your Stay Today</h2>
            <p class="mb-6">Available on both Android platforms. Start your journey with us now!</p>
            <div class="flex justify-center gap-4">
                <a href="https://mega.nz/folder/j08miK4Q#j7hMP4YuYuw_p4_Ikv9Crw" target="_blank"
                    class="bg-white text-gray-800 font-semibold py-3 px-6 rounded-full shadow-lg hover:bg-gray-100 transition duration-300">
                    <i class="fab fa-google-play mr-2"></i>Download for Android
                </a>

            </div>
        </section>
    </main>


    @livewireScripts
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
