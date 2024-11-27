<div class="z-10">
    <div class="justify-between flex p-1">
        <h1 class="text-2xl font-bold p-2">Report</h1>
        <div class="p-2">
            <button class="bg-cyan-400 font-medium text-white px-2 py-1 rounded " x-data
                x-on:click="$dispatch('open-modal', {name: 'generate-report-modal'})"> Create
                Report
            </button>

            <x-modal title="Generate Report" name="generate-report-modal" wire:ignore.self>

                @slot('body')
                    <form wire:submit.prevent="createReport">
                        <div class="grid grid-cols-2">
                            <div class="col-span-2">
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Report
                                    Type</label>
                                <select id="reportType" name="type" wire:model.live="type"
                                    wire:model.defer="changeReportType"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option value="">Select Report Type</option>
                                    <option value="Daily Revenue Report">Daily Revenue Report</option>
                                    <option value="Weekly Revenue Report">Weekly Revenue Report</option>
                                    <option value="Monthly Revenue Report">Monthly Revenue Report</option>
                                    <option value="Reservation Report">Reservation Report</option>
                                    <option value="Booking Report">Booking Report</option>
                                    <option value="Arrival and Departure Report">Arrival and Departure Report</option>
                                    <option value="Cancellation and No Show Report">Cancellation and No Show Report</option>
                                    <option value="Check In Report">Check In Report</option>
                                    <option value="Check Out Report">Check Out Report</option>
                                    <option value="Guest History Report">Guest History Report</option>
                                </select>
                                @error('type')
                                    <p class="text-red-500 text-xs italic mt-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                            @if ($type === 'Guest History Report')
                                <div class="col-span-2" id="guestField">
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                                        Guest</label>
                                    <select name="guest" wire:model="guest"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="">Select Guest</option>
                                        @foreach ($guests as $guest)
                                            <option value="{{ $guest->GuestId }}">
                                                {{ $guest->FirstName . ' ' . $guest->LastName }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('guest')
                                        <p class="text-red-500 text-xs italic mt-1">
                                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            @endif
                            <div class="col-span-2" id="dateFields">
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start
                                    Date</label>
                                <input type="date" wire:model="startdate" name="startdate"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Start Date" />
                                @error('startdate')
                                    <p class="text-red-500 text-xs italic mt-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="col-span-2" id="endDateField">
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
                                    Date</label>
                                <input type="date" wire:model="enddate" name="enddate"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="End Date" />
                                @error('enddate')
                                    <p class="text-red-500 text-xs italic mt-1">
                                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <button class="w-full bg-cyan-900 text-white py-2 rounded mt-4" type="submit">Create
                            Report</button>
                    </form>
                @endslot


            </x-modal>

        </div>
    </div>

    <div>
        <div class="bg-gray-50 rounded">
            <h5 class="mx-2 font-bold px-2 pt-2">Report</h5>
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
                            <th scope="col" class="px-2 py-3">Report</th>
                            <th scope="col" class="px-2 py-3">Generate By</th>
                            <th scope="col" class="px-2 py-3">Date</th>
                            <th scope="col" class="px-2 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($reports as $report)
                            <tr class="bg-white border-b text-xs text-center">
                                <td class="px-2 py-3">{{ $report->ReportName }}</td>
                                <td class="px-2 py-3">
                                    {{ $report->employee->FirstName . ' ' . $report->employee->LastName }}
                                </td>
                                <td class="px-2 py-3">
                                    {{ $report->Date }}{{ $report->EndDate ? ' - ' . $report->EndDate : '' }}
                                </td>

                                <td class="py-3 px-2 flex justify-center gap-2">
                                    <a class="bg-cyan-400 font-medium text-white px-2 py-1 rounded"
                                        href="{{ route('download-report', $report->ReportId) }}" download>
                                        Download
                                    </a>

                                    <a class="bg-red-400 font-medium text-white px-2 py-1 rounded"
                                        href="{{ route('download-report', $report->ReportId) }}" target="_blank">
                                        View
                                    </a>
                                </td>

                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>


            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const reportType = document.getElementById('reportType');
                    const dateFields = document.getElementById('dateFields');
                    const endDateField = document.getElementById('endDateField');
                    const guestField = document.getElementById('guestField');

                    function toggleDateFields() {
                        console.log(reportType.value);
                        const selectedValue = reportType.value;
                        if (selectedValue === 'Guest History Report') {
                            dateFields.style.display = 'block';
                            endDateField.style.display = 'block';
                            guestField.style.display = 'block';
                        } else {
                            dateFields.style.display = 'block';
                            endDateField.style.display = 'block';
                            guestField.style.display = 'none';
                        }
                    }

                    reportType.addEventListener('change', toggleDateFields);

                    toggleDateFields();
                });
            </script>
        </div>
    </div>
    <div wire:loading>
        <x-loader />
    </div>
</div>
