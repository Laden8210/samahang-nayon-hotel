<div class="flex min-h-full items-end justify-center p-1 text-center sm:items-center sm:p-0 w-full">
    <div class="grid grid-cols-4 gap-2">
        <div class="grid gap-4 col-span-3">
            <div class="shadow-lg">
                <img id="topImage" class="h-auto w-full max-w-full rounded-lg object-cover object-center md:h-[480px] "
                    src="data:image/png;base64,{{ base64_encode($topImage['PictureFile']) }}" alt="Top Image" />

            </div>
            <div class="grid grid-cols-7 gap-4">
                @foreach ($pictures as $picture)
                    <div class="">
                        <img src="data:image/png;base64,{{ base64_encode($picture['PictureFile']) }}"
                            class="object-cover object-center h-20 max-w-full rounded-lg cursor-pointer "
                            alt="gallery-image" />
                    </div>
                @endforeach

            </div>
        </div>

        <div class="w-64 text-left">
            <div class="bg-slate-50 shadow-lg drop-shadow p-2 rounded h-full">
                <h1 class="text-lg font-bold">{{ $room->RoomType }}</h1>
                <h2 class="text-xs">{{ $room->RoomPrice }}</h2>
                <hr>
                <p class="text-xs">{{ $room->Description }}</p>
            </div>
        </div>
    </div>


</div>
