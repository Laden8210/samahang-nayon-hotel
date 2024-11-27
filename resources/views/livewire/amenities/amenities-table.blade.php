<div>


    <x-modal title="Create New Amenities" name="add-modal-amenities">
        @slot('body')
            <form wire:submit.prevent="createAmenities">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <x-text-field1 name="name" placeholder="Type amenities name" model="name" label="Name"
                            type="text" />
                        @error('name')
                            <p class="text-red-500 text-xs italic mt-1"><i
                                    class="fas fa-exclamation-circle"></i></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                    <div class="col-span-2">

                        <x-text-field1 name="price" placeholder="Type amenities price" model="price" label="Price"
                            type="number" />
                        @error('price')
                            <p class="text-red-500 text-xs italic mt-1"><i
                                    class="fas fa-exclamation-circle"></i></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
                <button type="submit"
                    class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Add new amenities
                </button>
            </form>
        @endslot
    </x-modal>

    <div class="bg-gray-50 rounded">
        <h5 class="mx-2 font-bold px-2 pt-2">Amenities Information</h5>
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
                        <th scope="col" class="px-2 py-3">Type</th>
                        <th scope="col" class="px-2 py-3">Price</th>
                        <th scope="col" class="px-2 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($amenities as $amenity)
                        <tr class="bg-white border-b text-xs text-center">

                            <td class="px-2 py-3">{{ $amenity->Name }}</td>
                            <td class="px-2 py-3">
                                ₱{{ $amenity->Price }}</td>
                            <td class="py-3 px-2 flex justify-center">
                                <button x-data x-on:click="$dispatch('open-modal', {name: 'delete-modal'})"
                                    wire:click="setAmenitiesId({{ $amenity->AmenitiesId }})"
                                    class=" block px-4 py-2 hover:rounded-full hover:bg-red-100 text-red-600"
                                    type="button">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                                <button wire:click="updateAmenities({{ $amenity->AmenitiesId }})"
                                    class=" block px-4 py-2 hover:rounded-full hover:bg-blue-100 text-blue-600"
                                    type="button">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <x-modal title="Delete Amenities" name="delete-modal">

        @slot('body')
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you
                    sure
                    you want to delete this Amenity?</h3>
                <button wire:click="delete()" type="button"
                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                    Yes, I'm sure
                </button>
                <button x-on:click="$dispatch('close-modal', {name: 'delete-modal'})" type="button"
                    class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No,
                    cancel</button>
            </div>
        @endslot
    </x-modal>
    <x-modal title="Update Amenities" name="update-modal">
        @slot('body')
            <form wire:submit.prevent="update">
                <div class="grid gap-4 mb-4 grid-cols-2">

                    <div class="col-span-2">
                        <x-text-field1 name="update-name" placeholder="Enter amenities name" model="updateName"
                            label="Name" type="text" />
                    </div>
                    @error('updateName')
                        <p class="text-red-500 text-xs italic mt-1"><i
                                class="fas fa-exclamation-circle"></i></i>{{ $message }}
                        </p>
                    @enderror
                    <div class="col-span-2">
                        <x-text-field1 name="updatePrice" placeholder="Enter amenities price" model="updatePrice"
                            label="Price" type="number" />
                    </div>
                    @error('updatePrice')
                        <p class="text-red-500 text-xs italic mt-1"><i
                                class="fas fa-exclamation-circle"></i></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                <button type="submit"
                    class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Update amenities
                </button>
            </form>
        @endslot
    </x-modal>

    @if (session()->has('message'))
        <x-success-message-modal message="{{ session('message') }}" />
    @endif


</div>
