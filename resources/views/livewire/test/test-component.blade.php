<div class="w-full flex p-2 justify-center">

    <table class="w-full text-sm text-left rtl:text-right">

        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr class="bg-slate-50">
                <th >Header</th>
            </tr>
        </thead>
        <tbody>

                @foreach ($rooms as $room)

                <tr>


                <td class="p-2 border">{{ $room->name }}</td>
            </tr>
                @endforeach

        </tbody>
    </table>

</div>
