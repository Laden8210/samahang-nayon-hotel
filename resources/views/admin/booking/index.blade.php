@extends('layouts.app')

@section('title', 'Booking')
@section('content')


<div class="justify-between flex p-1">
    <h1 class="text-2xl font-bold p-2">Booking</h1>
    <div class="p-2">
        <a href="{{ route('createBooking') }}"
            class="bg-cyan-400 font-medium text-white px-2 py-1 rounded "> Create Booking
        </a>

    </div>
</div>


@livewire('booking.booking-table')
@endsection
