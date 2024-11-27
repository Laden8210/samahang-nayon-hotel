@extends('layouts.app')

@section('title', 'Amenities')
@section('content')
    <div class="justify-between flex p-1">
        <h1 class="text-2xl font-bold p-2">Amenities</h1>
        <div class="p-2">
            <button type="button" x-data x-on:click="$dispatch('open-modal', {name: 'add-modal-amenities'})"
                class="bg-cyan-400 font-medium text-white px-2 py-1 rounded "> Add Amenities
            </button>

        </div>
    </div>

    @livewire('amenities.amenities-table')



@endsection
