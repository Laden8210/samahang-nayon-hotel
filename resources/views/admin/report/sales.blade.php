<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $report->type }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        td {
            text-align: left;
        }

        td:first-child {
            text-align: left;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .logo img {
            width: 100px;
        }
    </style>
</head>

<body>

    <div class="logo">
        <img src="{{ public_path('img/logo.jpg') }}" alt="Logo">
    </div>

    <p style="text-align: center;">FEDERATION OF SOCSARGEN SAMAHANG NAYON</p>
    <p style="text-align: center;">COOPERATIVES (FSSNC)</p>
    <p style="text-align: center;">SAMAHANG NAYON HOTEL</p>


    <h3 style="text-align: center;">{{ $report->type ?? 'N/A' }}</h3>
    <p style="text-align: center;">
        Period: From {{ \Carbon\Carbon::parse($report->Date)->timezone('Asia/Manila')->format('F j, Y') }}
        to {{ \Carbon\Carbon::parse($report->EndDate ?? '')->timezone('Asia/Manila')->format('F j, Y') }}
    </p>


    @if ($report->type === 'Reservation Report')

        <table>
            <thead>
                <tr>
                    <th>Reservation Id</th>
                    <th>Guest Name</th>
                    <th>Room Type</th>
                    <th>Room No</th>
                    <th>Reservation Date</th>
                    <th>Check in Date</th>
                    <th>Check out date</th>
                    <th>No. of Nights</th>
                    <th>Reservation Source</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $reservation)
                    @if ($reservation->Status === 'Reserved')
                        <tr>
                            <td>{{ $reservation->ReservationId }}</td>
                            <td>{{ ucfirst($reservation->guest->FirstName) . ' ' . ucfirst($reservation->guest->LastName) }}
                            </td>
                            <td>{{ ucfirst($reservation->roomNumber->room->RoomType) }}</td>
                            <td>{{ $reservation->roomNumber->room_number }}</td>
                            <td>{{ \Carbon\Carbon::parse($reservation->DateCreated)->timezone('Asia/Manila')->format('F j, Y') }}
                            <td>{{ \Carbon\Carbon::parse($reservation->DateCheckIn)->timezone('Asia/Manila')->format('F j, Y') }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($reservation->DateCheckOut)->timezone('Asia/Manila')->format('F j, Y') }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($reservation->DateCheckIn)->diffInDays(\Carbon\Carbon::parse($reservation->DateCheckOut)) }}
                            </td>
                            <td>{{ $reservation->Source }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>


        </table>
    @elseif ($report->type === 'Booking Report')
        <table>
            <thead>
                <tr>
                    <th>Reservation Id</th>
                    <th>Guest Name</th>
                    <th>Room Type</th>
                    <th>Room No</th>
                    <th>Check in Date</th>
                    <th>Check out date</th>
                    <th>No. of Nights</th>
                    <th>Booking Source</th>
                </tr>
            </thead>
            <tbody>


                @foreach ($reservations as $reservation)
                    @if ($reservation->Status === 'Booked')
                        <tr>
                            <td>{{ $reservation->ReservationId }}</td>
                            <td>{{ ucfirst($reservation->guest->FirstName) . ' ' . ucfirst($reservation->guest->LastName) }}
                            </td>
                            <td>{{ ucfirst($reservation->roomNumber->room->RoomType) }}</td>
                            <td>{{ $reservation->roomNumber->room_number }}</td>
                            <td>{{ \Carbon\Carbon::parse($reservation->DateCheckIn)->timezone('Asia/Manila')->format('F j, Y') }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($reservation->DateCheckOut)->timezone('Asia/Manila')->format('F j, Y') }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($reservation->DateCheckIn)->diffInDays(\Carbon\Carbon::parse($reservation->DateCheckOut)) }}
                            </td>
                            <td>{{ ucfirst($reservation->Source) }}</td>
                        </tr>
                    @endif
                @endforeach

            </tbody>

        </table>
    @elseif ($report->type === 'Arrival and Departure Report')
        <h5>Scheduled Arrivals</h5>
        <table>

            <thead>
                <tr>
                    <th>Reservation Id</th>

                    <th>Guest Name</th>
                    <th>Room Type</th>
                    <th>Room No</th>
                    <th>Check in Date</th>
                    <th>Check in time</th>
                    <th>No. of Nights</th>
                    <th>Room Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($arrival as $reservation)
                    @if ($reservation->Status === 'Booked' || $reservation->Status === 'Reserved')
                        <tr>
                            <td>{{ $reservation->ReservationId }}</td>
                            <td>{{ ucfirst($reservation->guest->FirstName) . ' ' . ucfirst($reservation->guest->LastName) }}
                            </td>
                            <td>{{ ucfirst($reservation->roomNumber->room->RoomType) }}</td>
                            <td>{{ $reservation->roomNumber->room_number }}</td>
                            <td>{{ \Carbon\Carbon::parse($reservation->DateCheckIn)->timezone('Asia/Manila')->format('F j, Y') }}
                            </td>

                            <td>2:00 PM</td>
                            <td>{{ \Carbon\Carbon::parse($reservation->DateCheckIn)->diffInDays(\Carbon\Carbon::parse($reservation->DateCheckOut)) }}
                            </td>
                            <td>
                                @switch($reservation->Status)
                                    @case('Checked In')
                                        Occupied
                                    @break

                                    @case('Reserved')
                                        Reserved
                                    @break

                                    @case('Booked')
                                        Booked
                                    @break

                                    @case('Cancelled')
                                        Cancelled
                                    @break

                                    @default
                                        Unknown
                                @endswitch
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>

        </table>

        <h5>Scheduled Departures</h5>
        <table>

            <thead>
                <tr>
                    <th>Reservation Id</th>
                    <th>Guest Name</th>
                    <th>Room No</th>
                    <th>Room Type</th>
                    <th>Check out Date</th>
                    <th>Check out time</th>
                    <th>No. of Nights</th>
                    <th>Room Status</th>
                </tr>
            </thead>
            <tbody>

            <tbody>
                @if ($departure)
                    @foreach ($departure as $reservation)
                        @foreach ($reservation->checkInOuts as $checkInOut)
                            @if ($checkInOut->Type === 'Checked In')
                                <tr>
                                    <td>{{ $reservation->ReservationId }}</td>
                                    <td>{{ ucfirst($reservation->guest->FirstName) . ' ' . ucfirst($reservation->guest->LastName) }}
                                    </td>
                                    <td>{{ ucfirst($reservation->roomNumber->room->RoomType) }}</td>
                                    <td>{{ $reservation->roomNumber->room_number }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reservation->DateCheckOut)->timezone('Asia/Manila')->format('F j, Y') }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($checkInOut->TimeCreated)->format('h:i A') }}</td>

                                    <td>{{ \Carbon\Carbon::parse($reservation->DateCheckIn)->diffInDays(\Carbon\Carbon::parse($reservation->DateCheckOut)) }}
                                    </td>
                                    <td>
                                        @switch($checkInOut->Type)
                                            @case('Checked In')
                                                Occupied
                                            @break

                                            @case('Checked Out')
                                                Vacant
                                            @break

                                            @default
                                                Unknown
                                        @endswitch
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                @endif

            </tbody>

            </tbody>
        </table>
    @elseif ($report->type === 'Cancellation Report')
        <table>
            <h5>Scheduled Arrivals</h5>
            <thead>
                <tr>
                    <th>Reservation Id</th>
                    <th>Guest Name</th>
                    <th>Room Type</th>
                    <th>Room No</th>
                    <th>Reservation Date</th>
                    <th>Check In Date</th>
                    <th>Date of Cancellation</th>

                </tr>
            </thead>
            <tbody>


            <tbody>
                @foreach ($reservations as $reservation)
                    @if ($reservation->Status === 'Cancelled')
                        <tr>
                            <td>{{ $reservation->ReservationId }}</td>
                            <td>{{ ucfirst($reservation->guest->FirstName) . ' ' . ucfirst($reservation->guest->LastName) }}
                            </td>
                            <td>{{ ucfirst($reservation->roomNumber->room->RoomType) }}</td>
                            <td>{{ $reservation->roomNumber->room_number }}</td>
                            <td>{{ \Carbon\Carbon::parse($reservation->DateCreated)->timezone('Asia/Manila')->format('F j, Y') }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($reservation->DateCheckIn)->timezone('Asia/Manila')->format('F j, Y') }}
                            </td>

                            <td>{{ \Carbon\Carbon::parse($reservation->DateCancelled)->timezone('Asia/Manila')->format('F j, Y') }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>


            </tbody>
        </table>
    @elseif ($report->type === 'Guest History Report')
        @if ($reservations->isEmpty())
            <h3>No records found</h3>
        @else
            <h5>
                Guest Name:
                {{ ucfirst($reservations->first()->guest->FirstName) . ' ' . ucfirst($reservations->first()->guest->LastName) }}
            </h5>

            <h5>
                Guest Contact: {{ $reservations->first()->guest->ContactNumber }}
            </h5>
            <h5>
                Guest Email: {{ $reservations->first()->guest->EmailAddress }}
            </h5>

            <h5>
                Guest Address:
                {{ $reservations->first()->guest->Street . ', ' . $reservations->first()->guest->Brgy . ' ' . $reservations->first()->guest->City . ', ' . $reservations->first()->guest->Province }}
            </h5>

            <table>

                <thead>
                    <tr>
                        <th>Reservation Id</th>
                        <th>Stay Date</th>
                        <th>Room No</th>
                        <th>Room Type</th>

                        <th>Check In Date</th>
                        <th>Check Out Date</th>
                        <th>No. of nights</th>
                        <th>Booking Source</th>

                    </tr>
                </thead>
                <tbody>


                <tbody>
                    @foreach ($reservations as $reservation)
                        @if ($reservation->Status != 'Booked' || $reservation->Status === 'Reserved')
                            <tr>
                                <td>{{ $reservation->ReservationId }}</td>
                                <td>{{ \Carbon\Carbon::parse($reservation->DateCheckIn)->timezone('Asia/Manila')->format('F j, Y') }}
                                </td>
                                <td>{{ $reservation->roomNumber->room_number }}</td>
                                <td>{{ ucfirst($reservation->roomNumber->room->RoomType) }}</td>

                                <td>{{ \Carbon\Carbon::parse($reservation->DateCheckIn)->timezone('Asia/Manila')->format('F j, Y') }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($reservation->DateCheckOut)->timezone('Asia/Manila')->format('F j, Y') }}

                                <td>{{ \Carbon\Carbon::parse($reservation->DateCheckIn)->diffInDays(\Carbon\Carbon::parse($reservation->DateCheckOut)) }}
                                </td>
                                <td>{{ $reservation->Source }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>


                </tbody>
            </table>
        @endif
    @elseif ($report->type === 'Check Out Report')
        <table>
            <thead>
                <tr>
                    <th>Reservation Id</th>
                    <th>Guest Name</th>
                    <th>Room Type</th>
                    <th>Room No</th>
                    <th>Check Out Date</th>
                    <th>Check Out Time</th>


                </tr>
            </thead>
            <tbody>


                @foreach ($reservations as $reservation)
                    @foreach ($reservation->checkInOuts as $checkInOut)
                        @if ($checkInOut->Type === 'Checked Out')
                            <tr>
                                <td>{{ $reservation->ReservationId }}</td>
                                <td>{{ ucfirst($reservation->guest->FirstName) . ' ' . ucfirst($reservation->guest->LastName) }}
                                </td>

                                <td>{{ ucfirst($reservation->roomNumber->room->RoomType) }}</td>
                                <td>{{ $reservation->roomNumber->room_number }}</td>

                                <td>{{ \Carbon\Carbon::parse($checkInOut->DateCreated)->timezone('Asia/Manila')->format('F j, Y') }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($checkInOut->TimeCreated)->format('h:i A') }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach


            </tbody>

        </table>
    @elseif ($report->type === 'Check In Report')
        <table>
            <thead>
                <tr>
                    <th>Reservation Id</th>
                    <th>Guest Name</th>
                    <th>Room Type</th>
                    <th>Room No</th>
                    <th>Check in Date</th>
                    <th>Check in Time</th>


                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $reservation)
                    @foreach ($reservation->checkInOuts as $checkInOut)
                        @if ($checkInOut->Type === 'Checked In')
                            <tr>
                                <td>{{ $reservation->ReservationId }}</td>
                                <td>{{ ucfirst($reservation->guest->FirstName) . ' ' . ucfirst($reservation->guest->LastName) }}
                                </td>
                                <td>{{ ucfirst($reservation->roomNumber->room->RoomType) }}</td>
                                <td>{{ $reservation->roomNumber->room_number }}</td>
                                <td>{{ \Carbon\Carbon::parse($reservation->DateCheckIn)->timezone('Asia/Manila')->format('F j, Y') }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($checkInOut->TimeCreated)->format('h:i A') }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>


        </table>
    @elseif ($report->type === 'Cancellation and No Show Report')
        <table>
            <h5>Cancellation Report</h5>
            <thead>
                <tr>
                    <th>Reservation Id</th>
                    <th>Guest Name</th>
                    <th>Room No</th>
                    <th>Room Type</th>
                    <th>Reservation Date</th>
                    <th>Check In Date</th>
                    <th>Date of Cancellation</th>
                    <th>Remarks</th>

                </tr>
            </thead>

            <tbody>

                @foreach ($reservations as $reservation)
                    @if ($reservation->Status === 'Cancelled')
                        <tr>
                            <td>{{ $reservation->ReservationId }}</td>
                            <td>{{ ucfirst($reservation->guest->FirstName) . ' ' . ucfirst($reservation->guest->LastName) }}
                            </td>
                            <td>{{ ucfirst($reservation->roomNumber->room->RoomType) }}</td>
                            <td>{{ $reservation->roomNumber->room_number }}</td>

                            <td>{{ \Carbon\Carbon::parse($reservation->DateCreated)->timezone('Asia/Manila')->format('F j, Y') }}
                            </td>

                            <td>{{ \Carbon\Carbon::parse($reservation->DateCheckIn)->timezone('Asia/Manila')->format('F j, Y') }}
                            </td>

                            <td>{{ \Carbon\Carbon::parse($reservation->DateCancelled)->timezone('Asia/Manila')->format('F j, Y') }}
                            </td>

                            <td>
                                @if ($reservation->Status === 'Cancelled')
                                    Cancelled
                                @else
                                    No Show
                                @endif
                            </td>

                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <table>
            <thead>
                <tr>
                    <th>Reservation Id</th>
                    <th>Room No</th>
                    <th>Guest Name</th>
                    <th>Room Charge</th>
                    <th>Amenities Cost</th>
                    <th>Discount</th>
                    <th>Discount Type</th>
                    <th>Total Cost</th>
                    <th>Amount Paid</th>
                    <th>Status</th>

                </tr>
            </thead>
            <tbody>

                @if ($reservations->isEmpty())
                    <tr>
                        <td colspan="8">No records found</td>
                    </tr>
                @else
                    @foreach ($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->ReservationId }}</td>

                            <td>{{ $reservation->roomNumber->room_number }}</td>
                            <td>{{ ucfirst($reservation->guest->FirstName) . ' ' . ucfirst($reservation->guest->LastName) }}
                            </td>
                            <td>{{ number_format($reservation->TotalCost, 2) }}</td>
                            <td>{{ number_format($reservation->reservationAmenities->sum('TotalCost'), 2) }}
                            </td>
                            <td>{{ isset($reservation->Discount) ? number_format($reservation->Discount, 2) . '%' : '0%' }}
                            </td>

                            <td>{{ $reservation->DiscountType ?? 'No Discount' }}</td>
                            <td>{{ number_format($reservation->TotalCost - ($reservation->discount ?? 0) + +$reservation->reservationAmenities->sum('TotalCost'), 2) }}
                            </td>
                            <td>{{ number_format($reservation->payments->sum('AmountPaid'), 2) }}</td>

                            <td>
                                @if ($reservation->payments->sum('AmountPaid') >= $reservation->TotalCost)
                                    Downpayment
                                @else
                                    Full Payment
                                @endif
                            </td>
                        </tr>
                    @endforeach

                @endif

            </tbody>

        </table>
        <h3>Summary</h3>

        <table style="width: 40%; border: none; border-collapse: separate;">
            <tr>
                <td style="font-weight: bold; border: none">Total Room Charge:</td>
                <td style="text-align: right; font-weight: normal; border: none;">
                    <span style="font-family: DejaVu Sans;">&#x20B1;</span>
                    {{ number_format($reservations->sum('TotalCost'), 2) }}
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold; border: none">Total Amenities Cost:</td>
                <td style="text-align: right; font-weight: normal; border: none;">
                    <span style="font-family: DejaVu Sans;">&#x20B1;</span>
                    {{ number_format(
                        $reservations->sum(function ($reservation) {
                            return $reservation->reservationAmenities->sum('TotalCost');
                        }),
                        2,
                    ) }}
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold; border: none">Total Revenue:</td>
                <td style="text-align: right; font-weight: normal; border: none;">
                    <span style="font-family: DejaVu Sans;">&#x20B1;</span>
                    {{ number_format(
                        $reservations->sum(function ($reservation) {
                            return $reservation->TotalCost - ($reservation->discount ?? 0);
                        }) +
                            $reservations->sum(function ($reservation) {
                                return $reservation->reservationAmenities->sum('TotalCost');
                            }),
                        2,
                    ) }}
                </td>
            </tr>
        </table>


    @endif



    <div>
        <h3>
            Prepared by:
        </h3>
        <h2>
            {{ $report->employee->FirstName . ' ' . $report->employee->LastName }}
        </h2>
        <h5>{{ $report->employee->Position }}</h5>

        <h5>Generated on: {{ date('F j, Y') }}</h5>

    </div>


</body>

</html>
