@extends('layouts.app')

@section('title', 'Promotion ')
@section('content')

<div class="justify-between flex p-1">
    <h1 class="text-2xl font-bold p-2">Recent Payment</h1>

</div>
    @livewire('payment.payment-table')
@endsection


