@extends('layouts.main')

@section('title', 'Forget Password')
@section('content')

    <form action="{{ route('updatePassword') }}" method="POST">
        @csrf

        <div class="flex justify-center items-center min-h-screen w-full">
            <div class="p-5 bg-slate-50 rounded shadow" style="width: 450px">
                <div class="flex justify-center">
                    <img src="{{ asset('img/logo.jpg') }}" class="h-52 max-w-none py-5 rounded-full">
                </div>

                <input type="hidden" name="token" value="{{ Crypt::encrypt($employee->EmployeeId) }}">


                <h1 class="text-center text-2xl font-bold">Reset Password</h1>
                <p class="text-gray-400 text-sm text-center mb-10">Please kindly set your new password</p>

                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter New
                    Password</label>

                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-lock text-gray-400"></i>
                    </span>
                    <input type="password" id="password"
                        class="bg-gray-100 text-gray-900 placeholder-gray-400 px-3 py-2 pl-10 rounded-lg w-full focus:outline-none"
                        placeholder="Enter your password" name="password">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" id="toggle-password">
                        <i class="fas fa-eye text-gray-400" id="eye-icon-password"></i>
                    </span>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror

                <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm
                    Password</label>

                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-lock text-gray-400"></i>
                    </span>
                    <input type="password" id="confirm_password"
                        class="bg-gray-100 text-gray-900 placeholder-gray-400 px-3 py-2 pl-10 rounded-lg w-full focus:outline-none"
                        placeholder="Enter your password" name="confirm_password">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" id="ctoggle-password">
                        <i class="fas fa-eye text-gray-400" id="eye-icon-confirm-password"></i>
                    </span>
                </div>

                @error('confirm_password')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror

                @if (session('error'))
                    <p class="text-red-500 text-xs italic mt-1"><i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}</p>
                @endif

                <button class="w-full bg-cyan-500 text-white font-bold py-2 px-4 rounded mt-5">Confirm Password</button>
            </div>
        </div>

    </form>

    <script>
        // Toggle password visibility for the first password field
        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon-password');

            // Toggle between text and password input type
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle the eye icon
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });

        // Toggle password visibility for the confirm password field
        document.getElementById('ctoggle-password').addEventListener('click', function() {
            const cpasswordInput = document.getElementById('confirm_password');
            const eyeIcon = document.getElementById('eye-icon-confirm-password');

            // Toggle between text and password input type
            const type = cpasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            cpasswordInput.setAttribute('type', type);

            // Toggle the eye icon
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    </script>
@endsection
