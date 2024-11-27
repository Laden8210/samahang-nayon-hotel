<div class="hidden h-full relative" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="roomModal">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

    <div class="fixed inset-0 z-10 w-screen h-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start justify-between">
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Add Room</h3>
                        </div>
                        <div>
                            <button type="button"
                                onclick="document.getElementById('roomModal').style.display = 'none';"
                                class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <hr class="border border-1 mt-2">

                    @livewire('room.create-room')

                </div>
            </div>
        </div>
    </div>
</div>
