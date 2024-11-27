@extends('layouts.app')

@section('title', 'Update User ')
@section('content')
    <div class="">
        @livewire('user.update-user', ['userId' => $employee->EmployeeId])
    </div>

@endsection
