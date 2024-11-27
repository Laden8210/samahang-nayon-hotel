<div>

    <form wire:submit.prevent="createRoom">

        <div class="justify-between flex p-1">
            <h1 class="text-2xl font-bold p-2">Room</h1>
            <div class="p-2 flex gap-2">
                <a href="{{ route('rooms') }}" class="bg-red-400 font-medium text-white px-2 py-1 rounded ">
                    Cancel
                </a>
                <button type="submit" class="bg-cyan-400 font-medium text-white px-2 py-1 rounded ">
                    Create
                </button>
            </div>
        </div>

        @csrf

        <div class="flex justify-between ">
            <div class="p-2 bg-white h-fit rounded w-2/3 shadow mx-2">

                <h4 class="text-cyan-500 font-bold mx-4">Room Information</h4>

                <div class="flex justify-normal p-2 w-full">

                    <div class="w-1/2 mx-2">


                        <x-text-field1 name="roomType" placeholder="Room Name" model="roomType"
                            label="Enter the room name" type="text" />

                        @error('roomType')
                            <p class="text-red-500 text-xs italic mt-1"><i
                                    class="fas fa-exclamation-circle"></i></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="w-1/2 mx-2">
                        <x-combobox name="capacity" model="capacity" placeholder="Capacity" :options="[1, 2, 3, 4, 5, 6, 7, 8, 9, 10]" />

                        @error('capacity')
                            <p class="text-red-500 text-xs italic mt-1"><i
                                    class="fas fa-exclamation-circle"></i></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>


                <div class="flex justify-normal p-2 w-full">

                    <div class="w-1/2 mx-2">
                        <x-text-field1 name="room_rate" placeholder="Room Rate" model="rate"
                            label="Enter the room rate" type="number" />
                        @error('rate')
                            <p class="text-red-500 text-xs italic mt-1"><i
                                    class="fas fa-exclamation-circle"></i></i>{{ $message }}
                            </p>
                        @enderror
                    </div>


                </div>

                <div class="mx-4 h-1/2">

                    <x-text-area name="description" model="description" placeholder="Description" class="h-full" />
                    @error('description')
                        <p class="text-red-500 text-xs italic mt-1"><i
                                class="fas fa-exclamation-circle"></i></i>{{ $message }}
                        </p>
                    @enderror

                </div>
            </div>

            <div class="w-1/3 rounded shadow p-2 mx-2 bg-white">
                <div class="mt-4 flex justify-start px-2">
                    <h4 class="text-cyan-500 font-bold">Media</h4>
                    <h5 class="px-1">(Image)</h5>
                </div>
                <div class="mt-1">
                    <div class="flex justify-around">
                        <div class="rounded border-1 border-slate-600 h-40 w-full m-2">
                            <label for="uploadFile"
                                class="w-full bg-white text-gray-500 font-semibold text-base rounded  h-full flex flex-col items-center justify-center cursor-pointer border-2 border-gray-300 border-dashed mx-auto font-[sans-serif]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-11 mb-2 fill-gray-500"
                                    viewBox="0 0 32 32">
                                    <path
                                        d="M23.75 11.044a7.99 7.99 0 0 0-15.5-.009A8 8 0 0 0 9 27h3a1 1 0 0 0 0-2H9a6 6 0 0 1-.035-12 1.038 1.038 0 0 0 1.1-.854 5.991 5.991 0 0 1 11.862 0A1.08 1.08 0 0 0 23 13a6 6 0 0 1 0 12h-3a1 1 0 0 0 0 2h3a8 8 0 0 0 .75-15.956z"
                                        data-original="#000000" />
                                    <path
                                        d="M20.293 19.707a1 1 0 0 0 1.414-1.414l-5-5a1 1 0 0 0-1.414 0l-5 5a1 1 0 0 0 1.414 1.414L15 16.414V29a1 1 0 0 0 2 0V16.414z"
                                        data-original="#000000" />
                                </svg>
                                Upload files
                                <input type="file" id="uploadFile" class="hidden" multiple wire:model="pictures"
                                    accept="image/*" />
                            </label>
                        </div>
                    </div>
                </div>

                <div class="p-3">
                    <p class="text-gray-400 font-medium text-xs">Uploading Image</p>
                    <div
                        class="grid grid-cols-2 items-center my-2 gap-2 h-52 w-full p-2 overflow-x-hidden overflow-y-auto">

                        @foreach ($pictures as $picture)
                            <div class="relative group">
                                <img src="{{ $picture->temporaryUrl() }}" alt="image"
                                    class="rounded h-40 w-full transform transition-transform duration-300 hover:scale-105" />
                                <!-- Background overlay -->
                                <div
                                    class="absolute inset-0 bg-black opacity-0 group-hover:opacity-50 transition-opacity duration-300 rounded">
                                </div>
                                <!-- Remove button -->
                                <button type="button" wire:click="removePicture({{ $loop->index }})"
                                    class="text-white absolute inset-0 flex justify-center items-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-2xl ">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if (session()->has('message'))
                <x-success-message-modal message="{{ session('message') }}" />
            @endif

        </div>
    </form>

    <div wire:loading>
        <x-loader />
    </div>
</div>
