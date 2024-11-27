@extends('layouts.app')

@section('title', 'Booking')
@section('content')


@livewire('view-booking-details', ['ReservationId' => $ReservationId])

@endsection
