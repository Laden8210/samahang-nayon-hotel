<div class="bg-gray-50 rounded">
    <h5 class="mx-2 font-bold px-2 pt-2">Search</h5>
    <div class="w-full md:w-1/3 grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
        <!-- Search Input -->
        <div class="relative mb-4">
            <input type="text" wire:model.live.debounce.300ms="search"
                class="bg-gray-100 text-gray-900 placeholder-gray-400 px-3 py-2 w-full rounded-md outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Search . . . ">
            <span class="absolute inset-y-0 right-3 flex items-center">
                <i class="fas fa-search text-gray-400"></i>
            </span>
        </div>

        <!-- Status Dropdown -->
        <div class="mb-4">
            <select wire:model.live="status"
                class="bg-gray-100 text-gray-900 placeholder-gray-400 px-3 py-2 w-full rounded-md outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">View all</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->Status }}">{{ $status->Status }}</option>
                @endforeach

            </select>
        </div>
    </div>
    <div class="w-full flex p-2 justify-center rounded-lg drop-shadow">
        <table class="w-full h-full">
            <thead class="text-xs uppercase bg-gray-50">
                <tr class="text-center">
                    <th scope="col" class="px-2 py-3">Booking ID</th>
                    <th scope="col" class="px-2 py-3">Full Name</th>
                    <th scope="col" class="px-2 py-3">Room</th>
                    <th scope="col" class="px-2 py-3">Booking Date</th>
                    <th scope="col" class="px-2 py-3">Check In</th>
                    <th scope="col" class="px-2 py-3">Check Out</th>
                    <th scope="col" class="px-2 py-3">Status</th>
                    <th scope="col" class="px-2 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    use Carbon\Carbon;
                @endphp
                @foreach ($bookings as $booking)
                    <tr class="bg-white border-b text-xs text-center">

                        <td class="px-2 py-3">{{ $booking->ReservationId }}</td>
                        <td class="px-2 py-3">
                            {{ ucwords($booking->guest->FirstName) . ' ' . ($booking->guest->MiddleName ? ucwords($booking->guest->MiddleName) . ' ' : '') . ucwords($booking->guest->LastName) }}
                        </td>

                        <td class="py-3 px-2">
                            {{ ($booking->roomNumber->room->RoomType ?? '') . ' - #' . ($booking->roomNumber->room_number ?? '') }}

                        <td class="py-3 px-2">{{ Carbon::parse($booking->DateCreated)->format('F j, Y') }}</td>
                        <td class="py-3 px-2">{{ Carbon::parse($booking->DateCheckIn)->format('F j, Y') }}</td>
                        <td class="py-3 px-2">{{ Carbon::parse($booking->DateCheckOut)->format('F j, Y') }}</td>

                        <td class="py-3 px-2">
                            @if ($booking->Status == 'Pending')
                                <span
                                    class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded-full">{{ $booking->Status }}</span>
                            @endif
                            @if ($booking->Status == 'Booked')
                                <span
                                    class="bg-blue-200 text-blue-800 px-2 py-1 rounded-full">{{ $booking->Status }}</span>
                            @endif
                            @if ($booking->Status == 'Reserved')
                                <span
                                    class="bg-green-200 text-green-800 px-2 py-1 rounded-full">{{ $booking->Status }}</span>
                            @endif


                            @if ($booking->Status == 'Checked In')
                                <span
                                    class="bg-green-200 text-green-800 px-2 py-1 rounded-full">{{ $booking->Status }}</span>
                            @endif
                            @if ($booking->Status == 'Checked Out')
                                <span
                                    class="bg-blue-200 text-blue-800 px-2 py-1 rounded-full">{{ $booking->Status }}</span>
                            @endif
                            @if ($booking->Status == 'Cancelled')
                                <span
                                    class="bg-red-200 text-red-800 px-2 py-1 rounded-full">{{ $booking->Status }}</span>
                            @endif

                            @if ($booking->Status == 'Unconfirmed Reservation')
                                <span
                                    class="bg-violet-200 text-white-800 px-2 py-1 rounded-full">{{ $booking->Status }}</span>
                            @endif
                            @if ($booking->Status == 'No Show')
                                <span
                                    class="bg-gray-200 text-gray-800 px-2 py-1 rounded-full">{{ $booking->Status }}</span>
                            @endif


                        </td>
                        <td class="py-3 px-2">

                            <a href="{{ route('bookingDetails', $booking->ReservationId) }}"
                                class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">View</a>

                        </td>
                @endforeach
            </tbody>
        </table>


    </div>
    <div class="py-4 px-3">
        {{ $bookings->links('vendor.livewire.tailwind') }}

    </div>


</div>
