<div>


    <div class="justify-between flex p-1">
        <h1 class="text-2xl font-bold p-2">Promotion</h1>
        <div class="p-2">
            <button class="bg-cyan-400 font-medium text-white px-2 py-1 rounded " x-data
                x-on:click="$dispatch('open-modal', {name: 'add-modal'})"> Add Promotion
            </button>

        </div>
    </div>

    <div class="bg-gray-50 rounded">


        <h5 class="mx-2 font-bold px-2 pt-2">Promotion</h5>
        <div class="relative mb-4 w-1/3 mx-3">

            <input type="text" wire:model.live.debounce.300ms = "search"
                class="bg-gray-100 text-gray-900 placeholder-gray-400 px-3 py-2  rounded-lg w-full outline-none focus:outline-none"
                placeholder="Search . . . ">
            <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                <i class="fas fa-search text-gray-400"></i>
            </span>
        </div>

        <div class="w-full flex p-2 justify-center">
            <table class="w-full text-sm text-left rtl:text-right overflow-hidden">
                <thead class="text-xs uppercase bg-gray-100 ">
                    <tr class="text-center">

                        <th class="py-2">Promotion</th>
                        <th class="py-2">Discount</th>
                        <th class="py-2">Date</th>
                        <th class="py-2">End</th>
                        <th class="py-2">Date Created</th>
                        <th class="py-2">Action</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($promotions as $promotion)
                        <tr class="text-center">
                            <td class="py-2">
                                {{ $promotion->Promotion }}
                            </td>
                            <td class="py-2">
                                {{ $promotion->Discount }}%
                            </td>
                            <td class="py-2">
                                {{ \Carbon\Carbon::parse($promotion->StartDate)->format('F d, Y') }}
                            </td>
                            <td class="py-2">
                                {{ \Carbon\Carbon::parse($promotion->EndDate)->format('F d, Y') }}
                            </td>
                            <td class="py-2">
                                {{ \Carbon\Carbon::parse($promotion->DateCreated)->format('F d, Y') }}
                            </td>


                            <td class="py-2 flex items-center gap-2 justify-center">
                                <button type="button" class="bg-cyan-400 font-medium text-white px-2 py-1 rounded "
                                    wire:click="updatePromotion({{ $promotion->PromotionId }})" x-data
                                    x-on:click="$dispatch('open-modal', {name: 'update-modal'})"> <i class="fa fa-edit"
                                        aria-hidden="true"></i>
                                </button>
                                <button type="button" class="bg-red-400 font-medium text-white px-2 py-1 rounded "
                                    x-data
                                    x-on:click="$dispatch('open-modal', {name: 'delete-modal-{{ $promotion->PromotionId }}'})">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                                {{--
                            <button type="button" class="bg-green-400 font-medium text-white px-2 py-1 rounded " x-data
                                x-on:click="$dispatch('open-modal', {name: 'add-room-modal'})"> <i class="fa fa-eye"
                                    aria-hidden="true"></i>
                            </button> --}}
                            </td>
                        </tr>

                        <x-modal title="Delete Promotion" name="delete-modal-{{ $promotion->PromotionId }}">

                            @slot('body')
                                <div class="p-4 md:p-5 text-center">
                                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you
                                        sure
                                        you want to delete this Promotion?</h3>
                                    <button wire:click="deletePromotion({{ $promotion->PromotionId }})" type="button"
                                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                        Yes, I'm sure
                                    </button>
                                    <button
                                        x-on:click="$dispatch('open-modal', {name: 'delete-modal-{{ $promotion->PromotionId }}'})"
                                        type="button"
                                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No,
                                        cancel</button>
                                </div>
                            @endslot
                        </x-modal>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if (session()->has('message'))
            <x-success-message-modal message="{{ session('message') }}" />
        @endif



        <x-modal title="Add Promotion" name="add-modal">
            @slot('body')
                <form wire:submit.prevent="addPromotion">
                    <div class="grid gap-4 mb-4 grid-cols-2">

                        <div class="col-span-1">
                            <x-text-field1 name="update-name" placeholder="Enter Promotion Name" model="promotionName"
                                label="Promotion Name" type="text" />
                            @error('promotionName')
                                <!-- Display error message for promotionName -->
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-text-field1 name="updatePrice" placeholder="Enter Discount" model="discount"
                                label="Enter Discount" type="number" />
                            @error('discount')
                                <!-- Display error message for discount -->
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-text-field1 name="update-name" placeholder="Enter starting date" model="startingDate"
                                label="Enter starting date" type="date" />
                            @error('startingDate')
                                <!-- Display error message for startingDate -->
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-text-field1 name="updatePrice" placeholder="Enter ending date" model="endDate"
                                label="Enter ending date" type="date" />
                            @error('endDate')
                                <!-- Display error message for endDate -->
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-2 ">
                            <textarea wire:model="description"
                                class="h-28 mt-1  block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm"
                                placeholder="Enter Description"></textarea>
                            @error('description')
                                <!-- Display error message for description -->
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <div class="my-2 rounded shadow p-2">
                        <table class="w-full">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Selection
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Room Type
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white border-b hover:bg-gray-50">
                                @foreach ($rooms as $room)
                                    <tr class="text-center">
                                        <td class="px-6 py-4">
                                            <input type="checkbox" wire:model="selectRoom"
                                                value="{{ $room->RoomType }}">
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $room->RoomType }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                    <button type="submit"
                        class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Add Promotion
                    </button>
                </form>
            @endslot
        </x-modal>


        <x-modal title="Update Promotion" name="update-modal">
            @slot('body')
                <form wire:submit.prevent="savePromotion">
                    <div class="grid gap-4 mb-4 grid-cols-2">

                        <div class="col-span-1">
                            <x-text-field1 name="update-name" placeholder="Enter Promotion Name" model="promotionName"
                                label="Promotion Name" type="text" />
                            @error('promotionName')
                                <!-- Display error message for promotionName -->
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-text-field1 name="updatePrice" placeholder="Enter Discount" model="discount"
                                label="Enter Discount" type="number" />
                            @error('discount')
                                <!-- Display error message for discount -->
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-text-field1 name="update-name" placeholder="Enter starting date" model="startingDate"
                                label="Starting date" type="date" />
                            @error('startingDate')
                                <!-- Display error message for startingDate -->
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <x-text-field1 name="updatePrice" placeholder="Enter ending date" model="endDate"
                                label="Ending date" type="date" />
                            @error('endDate')
                                <!-- Display error message for endDate -->
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <textarea wire:model="description"
                                class="h-28 mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm"
                                placeholder="Enter Description"></textarea>
                            @error('description')
                                <!-- Display error message for description -->
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <div class="my-2 rounded shadow p-2">
                        <table class="w-full">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Selection
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Room Type
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white border-b hover:bg-gray-50">
                                @if ($discountedrooms)
                                    @foreach ($discountedrooms as $room)
                                        <tr class="text-center">
                                            <td class="px-6 py-4">
                                                <input type="checkbox" name="room[]" value="{{ $room->RoomType }}"
                                                    wire:model="updateSelectRoom" {{ $room->isChecked ? 'checked' : '' }}>
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $room->RoomType }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <button type="submit"
                        class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Update Promotion
                    </button>
                </form>
            @endslot
        </x-modal>

    </div>
</div>
