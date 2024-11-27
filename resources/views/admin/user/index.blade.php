@extends('layouts.app')

@section('title', 'User')
@section('content')


<div class="justify-between flex p-1">
    <h1 class="text-2xl font-bold p-2">User</h1>
    <div class="p-2">
        <a href="{{ route('addUser') }}"
            class="bg-cyan-400 font-medium text-white px-2 py-1 rounded "> Add User
        </a>

    </div>
</div>


@livewire('user.user-table')

@endsection
