@extends('layouts.app')

@section('title', 'Room ')
@section('content')
    <div class="">
        @livewire('room.update-room', ['roomId' => $room->RoomId])
    </div>

@endsection
