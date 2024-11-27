<div class="bg-gray-50 rounded">
    <h5 class="mx-2 font-bold px-2 pt-2">Search</h5>
    <div class="relative mb-4 w-1/3 mx-3">

        <input type="text" wire:model.live.debounce.300ms = "search"
            class="bg-gray-100 text-gray-900 placeholder-gray-400 px-3 py-2  rounded-lg w-full outline-none focus:outline-none"
            placeholder="Search . . . ">
        <span class="absolute inset-y-0 right-0 flex items-center pr-3">
            <i class="fas fa-search text-gray-400"></i>
        </span>
    </div>

    <div class="w-full flex p-2 justify-center rounded-lg drop-shadow">
        <table class="w-full h-full">
            <thead class="text-xs uppercase bg-gray-50">
                <tr class="text-center">
                    <th scope="col" class="px-2 py-3">ID</th>
                    <th scope="col" class="px-2 py-3">Name</th>
                    <th scope="col" class="px-2 py-3">Room Number</th>
                    <th scope="col" class="px-2 py-3">Date</th>
                    <th scope="col" class="px-2 py-3">Time</th>
                    <th scope="col" class="px-2 py-3">Status</th>

                    <th scope="col" class="px-2 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($checkInOuts as $checkInOut)
                    <tr class="bg-white border-b text-xs text-center">
                        <td class="px-2 py-3">{{ $checkInOut->CheckInOutId }}</td>
                        <td class="px-2 py-3">
                            {{ $checkInOut->guest->FirstName ?? ('' . ' ' . $checkInOut->guest->LastName ?? '') }}</td>
                        <td class="px-2 py-3">{{ $checkInOut->reservation->roomNumber->room_number ?? '' }}</td>
                        <td class="px-2 py-3">{{ \Carbon\Carbon::parse($checkInOut->DateCreated)->format('F d, Y') }}
                        </td>
                        <td class="px-2 py-3">{{ \Carbon\Carbon::parse($checkInOut->TimeCreated)->format('g:i A') }}
                        </td>

                        <td class="px-2 py-3">{{ $checkInOut->Type }}</td>
                        <td class="px-2 py-3">
                            <a href="{{ route('bookingDetails', $checkInOut->reservation->ReservationId ?? '') }}"
                                class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">View</a>

                        </td>
                @endforeach
            <tbody>

        </table>


    </div>

    <div class="py-4 px-3">
        {{ $checkInOuts->links('vendor.livewire.tailwind') }}

    </div>
</div>
