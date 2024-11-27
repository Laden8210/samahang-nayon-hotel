<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.css') }}">
</head>

<body>

    <nav class="flex drop-shadow h-auto bg-white p-2 justify-between">
        <div class="flex justify-normal items-center">
            <img src="{{ asset('img/logo.jpg') }}" class="mx-2 w-10">
            Samahang Nayon
        </div>

        <div class="flex justify-normal items-center px-2">
            <i class="fa-solid fa-globe mx-2"></i>
            <i class="fa-solid fa-circle-question"></i>
        </div>
    </nav>
    <main class="p-2 flex justify-center items-center h-screen">
        <div class="flex flex-col justify-center items-center w-1/2">
            <img src="{{ asset('img/logo.jpg') }}" class="h-80 max-w-none">
        </div>

        <div class="flex flex-col justify-center items-center w-2/3">

            <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <h1 class="text-2xl font-bold mb-2">Welcome back</h1>
                    <p class="text-gray-400 mb-5">Login to your account</p>
                    @if ($errors->has('email'))
                        <div class="bg-red-200 p-2 text-red-500 text-sm mb-2 rounded">
                            {{ $errors->first('email') }}
                        </div>
                    @endif

                    @if (session('message'))
                        <div class="bg-green-200 px-2 py-2 rounded text-green-500 mb-2">
                            {{ session('message') }}
                        </div>
                    @endif
                    <div class="relative mb-5">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </span>
                        <input type="text"
                            class="bg-gray-100 text-gray-900 placeholder-gray-400 px-3 py-2 pl-10 rounded-lg w-full focus:outline-none"
                            placeholder="Enter your email?" name="email">
                    </div>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-lock text-gray-400"></i>
                        </span>
                        <input type="password" id="password"
                            class="bg-gray-100 text-gray-900 placeholder-gray-400 px-3 py-2 pl-10 rounded-lg w-full focus:outline-none"
                            placeholder="Enter your password" name="password">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer"
                            id="toggle-password">
                            <i class="fas fa-eye text-gray-400" id="eye-icon"></i>
                        </span>
                    </div>

                    <div class="flex justify-between items-center mb-6 py-2 ">

                        <div><a href="{{ route('forget-password') }}" class="text-cyan-400">Forgot password?</a></div>
                    </div>

                    <button type="submit"
                        class="bg-cyan-400 text-white px-4 py-2 rounded-lg w-full hover:bg-cyan-500 hover:text-gray-100">Continue</button>
                </form>
            </div>
        </div>
    </main>
</body>


<script>
    document.getElementById('toggle-password').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
    });
</script>

</html>
