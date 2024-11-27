<div class="bg-gray-50 rounded">

    <div class="fle justify-normal w-1/2">
    <h5 class="mx-2 font-bold px-2 pt-2">Search</h5>
    <div class="flex items-center space-x-4"> <!-- Added space-x-4 for horizontal spacing -->

        <div class="relative mb-4 w-full mx-3 flex-grow mt-4"> <!-- flex-grow to make it responsive -->
            <input type="text"
                wire:model.live.debounce.300ms="search"
                class="bg-gray-100 text-gray-900 placeholder-gray-400 px-3 py-2 rounded-lg w-full outline-none focus:outline-none"
                placeholder="Search . . . ">
            <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                <i class="fas fa-search text-gray-400"></i>
            </span>
        </div>

        <div class="block w-48"> <!-- Set a fixed width for the select dropdown -->
            <select class="outline-none rounded border border-slate-500 w-full"
                wire:model.live.debounce.300ms="filterPayment">
                <option value="">All</option>
                <option value="Gcash">Gcash</option>
                <option value="Cash">Cash</option>
            </select>
        </div>
    </div>
</div>

<div class="w-full flex p-2 justify-center rounded-lg drop-shadow">
    <table class="w-full h-full">
        <thead class="text-xs uppercase bg-gray-50">
            <tr class="text-center">
                    <th  scope="col" class="px-2 py-3">Transaction Id{{ $filterPayment }}</th>
                    <th  scope="col" class="px-2 py-3">Payee Name</th>

                    <th  scope="col" class="px-2 py-3">Date</th>
                    <th  scope="col" class="px-2 py-3">Payment Method</th>
                    <th  scope="col" class="px-2 py-3">Amount</th>
                    <th  scope="col" class="px-2 py-3">Status</th>
                    <th  scope="col" class="px-2 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                <tr class="bg-white border-b text-xs text-center">
                        <td class="px-2 py-3">{{ $payment->ReferenceNumber }}</td>
                        {{-- <td class="py-2">{{ $payment->guest->FirstName }}</td> --}}
                        <td class="px-2 py-3">
                            {{ ($payment->guest->FirstName ?? '') . ' ' . ($payment->guest->LastName ?? '') }}
                        </td>


                        <td class="px-2 py-3">{{ $payment->DateCreated }}</td>
                        <td class="px-2 py-3">{{ $payment->PaymentType }}</td>
                        <td cclass="px-2 py-2">{{ $payment->AmountPaid }}</td>
                        <td class="px-2 py-3">{{ $payment->Status }}</td>
                        <td class="px-2 py-3">
                            <div class="flex justify-center">

                                {{-- <a href="{{ route('receipt', ['view' => $payment->ReferenceNumber]) }}" target="_blank"
                                    class="rounded-full hover:bg-blue-400 px-2 py-1 hover:text-white">
                                    <i class="fas fa-print"></i>
                                </a> --}}


                                <button type="button" x-data x-click="paymentDetailsModal = true"
                                    wire:click="selectPayments({{ $payment->PaymentId }})"
                                    class="rounded-full hover:bg-green-400 px-2 py-1 hover:text-white"><i
                                        class="fas fa-eye"></i></button>

                            </div>

                        </td>
                    </tr>
                @endforeach
            <tbody>

        </table>


    </div>


    <div class="py-4 px-3">
        <div class="flex justify-between items-center">
            <div class="flex-1">
                <p class="text-sm text-gray-700 dark:text-gray-400">
                    Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of
                    {{ $payments->total() }}
                    rooms
                </p>
            </div>
            <div class="flex items-center">
                @if ($payments->onFirstPage())
                    <span class="px-2 py-1 text-gray-500 bg-gray-200 rounded-l cursor-not-allowed">Previous</span>
                @else
                    <a href="{{ $payments->previousPageUrl() }}"
                        class="px-2 py-1 bg-cyan-500 text-white rounded-l hover:bg-cyan-600">Previous</a>
                @endif

                @if ($payments->hasMorePages())
                    <a href="{{ $payments->nextPageUrl() }}"
                        class="px-2 py-1 bg-cyan-500 text-white rounded-r hover:bg-cyan-600">Next</a>
                @else
                    <span class="px-2 py-1 text-gray-500 bg-gray-200 rounded-r cursor-not-allowed">Next</span>
                @endif
            </div>
        </div>


    </div>

    <x-modal name="payment-details-modal" title="Payment Details">
        @slot('body')
            <div class="p-6 md:p-8 bg-gray-50 rounded-lg shadow-lg">
                <div class="grid grid-cols-2 gap-4">
                    <!-- Labels Column -->
                    <div class="font-semibold text-gray-700 space-y-4">
                        <p class="text-sm">Transaction ID:</p>
                        <p class="text-sm">Payee Name:</p>
                        <p class="text-sm">Date:</p>
                        <p class="text-sm">Payment Method:</p>
                        <p class="text-sm">Amount:</p>
                        <p class="text-sm">Status:</p>
                    </div>

                    <!-- Values Column -->
                    <div class="space-y-4 text-gray-800">
                        @if ($selectPayment)
                            <p class="text-sm font-medium">{{ $selectPayment->ReferenceNumber }}</p>
                            <p class="text-sm font-medium">
                                {{ ucwords($selectPayment->guest->FirstName) . ' ' . ($selectPayment->guest->MiddleName ? strtoupper($selectPayment->guest->MiddleName[0]) . '. ' : '') . ucwords($selectPayment->guest->LastName) }}
                            </p>
                            <p class="text-sm font-medium">{{ \Carbon\Carbon::parse($selectPayment->DateCreated)->format('F d, Y') }}</p>
                            <p class="text-sm font-medium">{{ $selectPayment->PaymentType }}</p>
                            <p class="text-sm font-medium">₱{{ number_format($selectPayment->AmountPaid, 2) }}</p>
                            <p class="text-sm font-medium">{{ $selectPayment->Status }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endslot
    </x-modal>


</div>
