@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h1 class="text-2xl font-bold p-2">Dashboard</h1>

    <div class="bg-white p-4 rounded">
        <h2 class="font-bold">Overview</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-2">
            <x-dashboard-card title="Available Room" count="{{ $availableRooms }}" />
            <x-dashboard-card title="Occupied Room" count="{{ $occupiedRooms }}" />
            <x-dashboard-card title="Today's Check In" count="{{ $totalCheckIn }}" />
            <x-dashboard-card title="Today's Check Out" count="{{ $totalCheckOut }}" />
            <x-dashboard-card title="Today's Booking" count="{{ $totalBooking }}" />
            <x-dashboard-card title="Today's Reservation" count="{{ $totalReservation }}" />
            <x-dashboard-card title="Total Guest" count="{{ $user }}" />
        </div>

        <div class="grid grid-cols-1 w-full h-96 bg-white p-4 mt-10 rounded shadow">

            <canvas id="myChart" class="w-full h-full"></canvas>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const data = {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Occupancy Rate (%)',
                data: {!! json_encode($roomOccupancyData) !!},
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    // Add other colors as needed
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    // Add other colors as needed
                ],
                borderWidth: 1
            }]
        };

        const config = {
            type: 'bar',
            data: data,
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            },
                            stepSize: 20
                        },
                        title: {
                            display: true,
                            text: 'Occupancy Rate (%)',
                            color: '#333',
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Months',
                            color: '#333',
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#333',
                        }
                    }
                }
            }
        };

        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
    </script>


@endsection
