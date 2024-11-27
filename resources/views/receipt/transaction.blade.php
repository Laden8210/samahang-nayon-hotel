    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Thermal Printer Receipt</title>
        <style>
            body {
                font-family: 'Arial', sans-serif;
                font-size: 10px;

                padding: 0;
            }

            .receipt-container {
                width: 65mm;

                padding: 1px;
            }

            .logo {
                text-align: center;
                margin-bottom: 10px;
            }

            .logo img {
                width: 50px;
                height: 50px;
                object-fit: contain;
            }

            .hotel-name {
                text-align: center;
                font-size: 16px;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .receipt-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 10px;
            }

            .receipt-table th,
            .receipt-table td {

                padding: 4px 0;
            }

            .receipt-table th {
                border-bottom: 1px solid #000;
            }

            .total-amount {
                text-align: right;
                font-weight: bold;
                margin-top: 10px;
            }

            .customer-info {
                margin-top: 10px;
            }

            .reference-number {
                text-align: left;
                font-size: 12px;
                font-weight: bold;
                margin-top: 10px;
            }

            /* Ensure content fits in thermal printer width */
            @media print {
                .receipt-container {
                    width: 100%;
                }
            }
        </style>
    </head>

    <body>
        <div class="receipt-container">
            <div class="logo">
                <img src="{{ public_path('img/logo.jpg') }}" alt="Hotel Logo">
            </div>
            <div class="hotel-name">Samahang Nayon Hotel</div>
            <table class="receipt-table">
                <thead>
                    <tr>
                        <th>Room</th>
                        <th>Night(s)</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $reservation->roomNumber->room->RoomType }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($reservation->DateCheckIn)->diffInDays(\Carbon\Carbon::parse($reservation->DateCheckOut)) }}
                        </td>
                        <td>{{ number_format($reservation->TotalCost, 2) }}</td>
                    </tr>
                </tbody>


                </tbody>
            </table>

            @php
                $total = 0;

                $total = $total + $reservation->TotalCost;
            @endphp

            <table class="receipt-table">
                <thead>
                    <tr>
                        <th>Amenities</th>
                        <th>Qty</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach ($reservation->reservationAmenities as $amenities)
                            <td>{{ $amenities->amenity->Name }}</td>
                            <td>{{ $amenities->Quantity }}</td>
                            <td>{{ number_format($amenities->TotalCost, 2) }}</td>
                            @php
                                $total = $total + $amenities->TotalCost;
                            @endphp
                        @endforeach

                    </tr>
                </tbody>


                </tbody>
            </table>

            <div class="total-amount">Total: {{$total}}</div>
            <div class="customer-info">
                <p>Customer: {{$reservation->guest->FirstName .' ' .$reservation->guest->LastName}}</p>
                <p>Room: {{$reservation->roomNumber->room_number}}</p>
            </div>
            <div class="reference-number">Ref #: 123456789</div>
        </div>
    </body>

    </html>
