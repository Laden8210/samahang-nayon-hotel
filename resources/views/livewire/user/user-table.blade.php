<div class="bg-gray-50 rounded">
    <h5 class="mx-2 font-bold px-2 pt-2">User Information</h5>
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
                    <th scope="col" class="px-2 py-3">No</th>

                    <th scope="col" class="px-2 py-3">Full Name</th>
                    <th scope="col" class="px-2 py-3">Position</th>
                    <th scope="col" class="px-2 py-3">Email</th>
                    <th scope="col" class="px-2 py-3">Contact Number</th>
                    <th scope="col" class="px-2 py-3">Status</th>
                    <th scope="col" class="px-2 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $u)
                    <tr class="bg-white border-b text-xs text-center">
                        <td class="px-2 py-3">{{ $u->EmployeeId }}</td>

                        <td class="px-2 py-3">
                            {{ $u->FirstName . (empty($u->MiddleName) ? ' ' : ' ' . $u->MiddleName[0] . '. ') . $u->LastName }}
                        </td>
                        <td class="px-2 py-3">{{ $u->Position }}</td>
                        <td class="px-2 py-3">{{ utf8_encode($u->email) }}</td>
                        <td class="px-2 py-3">{{ $u->ContactNumber }}</td>
                        <td class="px-2 py-3">
                            @if ($u->Status == 'Active')
                                <span
                                    class="px-2 py-1 bg-green-500  text-white rounded-full text-xs">{{ $u->Status }}</span>
                            @else
                                <span
                                    class="px-2 py-1 bg-red-500 text-white rounded-full text-xs">{{ $u->Status }}</span>
                            @endif
                        <td class="px-2 py-3 flex justify-center gap-2">

                            <button wire:click.prevent="viewUser({{ $u->EmployeeId }})"
                                class="block p-2 bg-blue-600 hover:bg-blue-500 text-white rounded"><i class="fa fa-eye"
                                    aria-hidden="true"></i></button>

                            <a href="{{ route('updateUser', $u->EmployeeId) }}"
                                class="block p-2 bg-yellow-600 hover:bg-yellow-500 text-white rounded"><i
                                    class="fa fa-edit" aria-hidden="true"></i></a>


                            @if ($u->Status == 'Active')
                                <button wire:click="changeStatus({{ $u->EmployeeId }})"
                                    class="block p-2 bg-orange-600  hover:bg-orange-500 text-white rounded">
                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                @else
                                    <button wire:click="changeStatus({{ $u->EmployeeId }})"
                                        class="block p-2 bg-green-600  hover:bg-green-500 text-white rounded">
                                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                            @endif
                            </button>

                            <button wire:click.prevent="selectUser({{ $u->EmployeeId }})"
                                class="block p-2 bg-red-600  hover:bg-red-500 text-white rounded"><i class="fa fa-trash"
                                    aria-hidden="true"></i></button>
                        </td>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex items-center">
        {{ $employees->links('vendor.livewire.tailwind') }}
    </div>

    @if ($viewUserModal)
        <div class="fixed z-50 inset-0 flex items-center justify-center">
            <div class="fixed inset-0 bg-black opacity-50"></div>
            <div class="relative p-6 w-full max-w-lg max-h-full">
                <div class="relative bg-white rounded-lg shadow-lg dark:bg-gray-800 overflow-hidden">

                    <!-- Close Button -->
                    <button type="button"
                        class="absolute top-4 right-4 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        wire:click.prevent="cancelView">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>

                    <!-- Modal Header -->
                    <div class="px-6 py-4 bg-blue-500 text-white rounded-t-lg">
                        <h3 class="text-lg font-semibold">User Information</h3>
                    </div>

                    <!-- Modal Content -->
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-4 gap-y-4 gap-x-6">
                            <!-- Each section with divider line after -->
                            <div class="col-span-2 border-b pb-2">
                                <p class="text-gray-600 dark:text-gray-300 font-medium">Full Name:</p>
                                <p class="text-gray-900 dark:text-gray-100">
                                    {{ $user->FirstName . ' ' . $user->LastName . ' ' . ($user->MiddleName[0] ?? '') }}
                                </p>

                            </div>

                            <div class="col-span-2 border-b pb-2">
                                <p class="text-gray-600 dark:text-gray-300 font-medium">Middle Name:</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ $user->MiddleName }}</p>
                            </div>
                            <div class="col-span-2 border-b pb-2">
                                <p class="text-gray-600 dark:text-gray-300 font-medium">Position:</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ $user->Position }}</p>
                            </div>
                            <div class="col-span-2 border-b pb-2">
                                <p class="text-gray-600 dark:text-gray-300 font-medium">Status:</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ $user->Status }}</p>
                            </div>
                            <div class="col-span-2 border-b pb-2">
                                <p class="text-gray-600 dark:text-gray-300 font-medium">Contact Number:</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ $user->ContactNumber }}</p>
                            </div>
                            <div class="col-span-2 border-b pb-2">
                                <p class="text-gray-600 dark:text-gray-300 font-medium">Gender:</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ $user->Gender }}</p>
                            </div>
                            <div class="col-span-2 border-b pb-2">
                                <p class="text-gray-600 dark:text-gray-300 font-medium">Birthdate:</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ $user->Birthdate }}</p>
                            </div>
                            <div class="col-span-2 border-b pb-2">
                                <p class="text-gray-600 dark:text-gray-300 font-medium">Address:</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ $user->Street }}, {{ $user->Brgy }},
                                    {{ $user->City }}, {{ $user->Province }}</p>
                            </div>
                            <div class="col-span-2 border-b pb-2">
                                <p class="text-gray-600 dark:text-gray-300 font-medium">Email:</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ $user->email }}</p>
                            </div>
                            <div class="col-span-2 border-b pb-2">
                                <p class="text-gray-600 dark:text-gray-300 font-medium">Date Created:</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ $user->DateCreated }}</p>
                            </div>

                            <div class="col-span-2">
                                <p class="text-gray-600 dark:text-gray-300 font-medium">Verified:</p>
                                <p class="text-gray-900 dark:text-gray-100">{{ $user->is_verified ? 'Yes' : 'No' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if ($deleteUserModal)
        <div class="fixed z-50 inset-0 flex items-center justify-center">
            <div class="fixed inset-0 bg-gray-300 opacity-40"></div>
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        wire:click.prevent="cancelDelete">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-4 md:p-5 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure
                            you want to delete this user?</h3>

                        <form wire:submit.prevent="deleteUser">
                            <button
                                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                Yes, I'm sure
                            </button>
                            <button wire:click.prevent="cancelDelete" type="button"
                                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                No, cancel
                            </button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if (session()->has('message'))
        <x-modal.success-message-modal message="{{ session('message') }}" />
    @endif
    <div wire:loading>
        <x-loader />
    </div>

</div>
