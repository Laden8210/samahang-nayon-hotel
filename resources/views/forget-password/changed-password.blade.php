@extends('layouts.main')

@section('title', 'Forget Password')
@section('content')

    <div class="flex justify-center items-center min-h-screen w-full">
        <div class="p-5 bg-slate-50 rounded shadow" style="width: 450px">
            <div class="flex justify-center w-full">
                <img src="{{ asset('img/logo.jpg') }}" class="h-52 max-w-none py-5 rounded-full">
            </div>

            <h1 class="text-center text-2xl font-bold">Password changed!</h1>
            <p class="text-gray-400 text-sm text-center mb-10">You've successfully completed your password reset</p>

            <div class="w-full flex justify-center text-center">
                <a href="{{route('index')}}" class="w-full bg-cyan-500 text-white font-bold py-2 px-4 rounded mt-5">Login Now</a>
            </div>

        </div>
    </div>

@endsection
