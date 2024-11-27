@extends('layouts.app')

@section('title', 'Settings')
@section('content')

    <div class="container mx-auto p-4">
        <!-- User Header -->
        <div class="shadow-lg bg-white rounded-lg px-6 py-4 flex justify-between items-center mb-6">
            <div class="flex items-center">
                <h1 class="font-bold text-xl">Welcome, {{ $user->FirstName }} {{ $user->MiddleName }} {{ $user->LastName }}
                </h1>
            </div>
            <div>
                <a href="{{ route('logout') }}"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded text-white font-semibold transition duration-200">Logout</a>
            </div>
        </div>

        <!-- User Information Section -->
        <div class="shadow-lg bg-white rounded-lg p-6">
            <div class="flex justify-between items-center px-2 py-2">
                <h2 class="font-bold text-2xl mb-4">User Information</h2>
                <button class="bg-cyan-600 hover:bg-cyan-900 rounded px-2 py-3 text-white text-xs"
                x-data x-on:click="$dispatch('open-modal', {name: 'change-passsword'})"
                >Change Password</button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-gray-100 p-4 rounded-md">
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                </div>

                <div class="bg-gray-100 p-4 rounded-md">
                    <p><strong>Position:</strong> {{ $user->Position }}</p>
                </div>
                <div class="bg-gray-100 p-4 rounded-md">
                    <p><strong>Status:</strong> {{ $user->Status }}</p>
                </div>
                <div class="bg-gray-100 p-4 rounded-md">
                    <p><strong>Contact Number:</strong> {{ $user->ContactNumber }}</p>
                </div>
                <div class="bg-gray-100 p-4 rounded-md">
                    <p><strong>Gender:</strong> {{ $user->Gender }}</p>
                </div>
                <div class="bg-gray-100 p-4 rounded-md">
                    <p><strong>Birthdate:</strong> {{ \Carbon\Carbon::parse($user->Birthdate)->format('F d, Y') }}</p>
                </div>
                <div class="bg-gray-100 p-4 rounded-md">
                    <p><strong>Address:</strong> {{ $user->Street }}, {{ $user->Brgy }}, {{ $user->City }},
                        {{ $user->Province }}</p>
                </div>
            </div>
        </div>
    </div>

    @livewire('setting.change-password')

@endsection
