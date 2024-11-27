@extends('layouts.main')

@section('title', 'Forget Password')
@section('content')

    <form action="{{ route('request-otp') }}" method="POST">
        @csrf

        <div class="flex justify-center items-center min-h-screen">
            <div class="p-5 bg-slate-50 rounded shadow" style="width: 450px">
                <div class="flex justify-center">
                    <img src="{{ asset('img/logo.jpg') }}" class="h-52 max-w-none py-5 rounded-full">
                </div>

                <h1 class="text-center text-2xl font-bold">Forget your password?</h1>
                <p class="text-gray-400 text-sm text-center mb-10">Enter your phone number or email and we'll send you a link to reset your password.</p>

                <label for="phoneEmail" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter Phone Number or email</label>
                <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-50"
                       type="text" name="phoneEmail" id="phoneEmail" placeholder="Phone Number or email" value="{{ old('phoneEmail') }}">

                @error('phoneEmail')
                    <p class="text-red-500 text-xs italic mt-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                @enderror

                @if (session('error'))
                    <p class="text-red-500 text-xs italic mt-1"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</p>
                @endif

                <button type="submit" class="w-full bg-cyan-500 text-white font-bold py-2 px-4 rounded mt-5">Send OTP</button>

                <div class="flex justify-center mt-5">
                    <a href="{{ route('index') }}" class="font-medium text-sm"><i class="fas fa-chevron-left"></i> Back to login</a>
                </div>

            </div>
        </div>

    </form>

@endsection
