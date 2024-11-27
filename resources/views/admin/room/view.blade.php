@extends('layouts.app')

@section('title', 'Room ')
@section('content')
    <div class="w-full">
        @livewire('room.view-room', ['roomId' => $room->RoomId])
    </div>

@endsection
