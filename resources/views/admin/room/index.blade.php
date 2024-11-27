@extends('layouts.app')

@section('title', 'Room ')
@section('content')

    <div class="justify-between flex p-1">
        <h1 class="text-2xl font-bold p-2">Room</h1>
        <div class="p-2">
            <a href="{{ route('addRoom') }}"
                class="bg-green-700 font-medium text-white px-2 py-2 rounded "> Add Room
            </a>

        </div>
    </div>

    @livewire('room.room-table', ['lazy' => true])


    <x-room-modal/>

@endsection


