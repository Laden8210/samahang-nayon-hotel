<div class="bg-gray-50 rounded">
    <h5 class="mx-2 mb-5 font-bold px-2 pt-2">Activity Details</h5>

    <div class="relative mb-4 w-1/3 mx-3">

        <input type="text" wire:model.live.debounce.300ms = "search"
            class="bg-gray-100 text-gray-900 placeholder-gray-400 px-3 py-2 w-full outline-none focus:outline-none"
            placeholder="Search . . . ">
        <span class="absolute inset-y-0 right-0 flex items-center pr-3">
            <i class="fas fa-search text-gray-400"></i>
        </span>
    </div>

    <div class="w-full flex p-2 justify-center rounded-lg drop-shadow">
        <table class="w-full h-full">
            <thead class="text-xs uppercase bg-gray-50">
                <tr class="text-center">
                    <th scope="col" class="px-2 py-3">No</th>
                    <th scope="col" class="px-2 py-3">TYPE</th>
                    <th scope="col" class="px-2 py-3">ACTIVITY</th>
                    <th scope="col" class="px-2 py-3">DATE</th>
                    <th scope="col" class="px-2 py-3">TIME</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr class="bg-white border-b text-xs text-center">
                        <td class="px-2 py-3">{{ $log->id }}</td>
                        <td class="px-2 py-3">{{ $log->action }}</td>

                        <td class="px-2 py-3">{{ $log->log }}</td>
                        <td class="px-2 py-3">{{ $log->created_at->format('M d, Y') }}</td>
                        <td class="px-2 py-3">{{ $log->created_at->format('h:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>


</div>
