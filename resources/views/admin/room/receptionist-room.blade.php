@extends('layouts.app')

@section('title', 'Promotion ')
@section('content')

    <div class="grid grid-cols-5 gap-2 ">

        <div class="shadow-lg rounded-lg col-span-5 p-2">
            <h1 class="text-xl font-bold p-2">Available Room</h1>
            @livewire('booking.room-list')
        </div>





@endsection
