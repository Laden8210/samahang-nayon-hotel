<div class="space-y-4 mt-1">

    <div class="w-1/2">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Date</label>
        <input name="date" placeholder="Select Date" type="date"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-50"
            wire:model.live="date" />
    </div>


    <div class="grid grid-cols-10 gap-2">
        @for ($i = 0; $i < 3; $i++)
            @for ($x = 0; $x < 10; $x++)
                @php
                    $roomNumber = ($i + 1) * 100 + $x + 1;
                    $room = null;

                    if ($roomNumbers) {
                        foreach ($roomNumbers as $r) {
                            if ($r->room_number == $roomNumber) {
                                $room = $r;
                                break;
                            }
                        }
                    }
                @endphp

                <div class="bg-white  shadow-md rounded-lg hover:shadow-lg transition-shadow duration-200">
                    <a
                    @if ($room && $room->isBooked == 'false')
                        href="{{ route('createBooking', $room->RoomId) }}"
                    @endif

                    class="px-1 py-1 flex flex-col items-center justify-center h-72">

                        @if ($room)
                            <!-- Room Image -->
                            <div class="w-full h-32 bg-gray-200 rounded-lg mb-3 overflow-hidden">
                                @if ($room->room->roomPictures->isNotEmpty())
                                    <img src="data:image/png;base64,{{ base64_encode($room->room->roomPictures->first()->PictureFile) }}"
                                        alt="{{ $room->room->RoomType }}" class="object-cover w-full h-full">
                                @else
                                    <img src="https://community.softr.io/uploads/db9110/original/2X/7/74e6e7e382d0ff5d7773ca9a87e6f6f8817a68a6.jpeg"
                                        alt="No Image Available" class="object-cover w-full h-full">
                                @endif
                            </div>

                            <!-- Room Details -->
                            <div class="grid grid-cols-2 gap-y-1 w-full text-gray-800 text-xs">

                                <p class="text-center col-span-2 font-bold">{{ $room->room->RoomType }}</p>

                                <p class="text-left font-semibold">Room:</p>
                                <p class="text-right">{{ $roomNumber }}</p>

                                <p class="text-left font-semibold">Price:</p>
                                <p class="text-right">
                                    @if ($room->discount)
                                        <del>₱{{ number_format($room->room->RoomPrice, 2) }}</del>
                                    @else
                                        ₱{{ number_format($room->room->RoomPrice, 2) }}
                                    @endif
                                </p>

                                @if ($room->discount)
                                    <p class="text-left font-semibold">New Price:</p>
                                    <p class="text-right text-red-600 font-bold">
                                        ₱{{ number_format($room->room->RoomPrice - ($room->room->RoomPrice * ($room->discount / 100)), 2) }}
                                    </p>
                                @endif
                                <p class="text-left font-semibold">Capacity:</p>
                                <p class="text-right">{{ $room->room->Capacity }} people</p>

                                <!-- Room Status -->

                                <p class="text-right col-span-2 mt-2">
                                    <span
                                        class="px-3 py-1 rounded-full text-white text-xs font-semibold
                                        {{ $room->isBooked == 'false' ? 'bg-red-500' : 'bg-green-500'}}">
                                        {{ $room->isBooked == 'false' ?  'Not Available' :'Available'  }}
                                    </span>
                                </p>
                            </div>
                        @else
                            <!-- Default Room Card when room is not available -->
                            <div class="w-full h-32 bg-gray-200 rounded-lg mb-3 overflow-hidden">
                                <img src="https://community.softr.io/uploads/db9110/original/2X/7/74e6e7e382d0ff5d7773ca9a87e6f6f8817a68a6.jpeg"
                                    alt="No Image Available" class="object-cover w-full h-full">
                            </div>

                            <div class="grid grid-cols-2 gap-y-1  w-full text-gray-800 text-xs">
                                <p class="text-center col-span-2 font-bold">N/A</p>
                                <p class="text-left font-semibold">Room:</p>
                                <p class="text-right">{{ $roomNumber }}</p>

                                <p class="text-left font-semibold">Price:</p>
                                <p class="text-right">N/A</p>

                                <p class="text-left font-semibold">Capacity:</p>
                                <p class="text-right">N/A</p>

                                <!-- Room Status -->

                                <p class="text-right col-span-2 mt-2">
                                    <span class="px-3 py-1 rounded-full text-white text-xs font-semibold bg-red-500">
                                        Not Available
                                    </span>
                                </p>
                            </div>
                        @endif
                        </a>
                </div>
            @endfor
        @endfor
    </div>

    <div wire:loading>
        <x-loader />
    </div>
</div>
