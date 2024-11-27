<div class="relative z-50" id="default-modal" tabindex="-1" aria-hidden="true">
    <div id="modal-backdrop" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div id="modal-panel" class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Message</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">{{$message}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button"
                            class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:ml-3 sm:w-auto"
                            onclick="
                                document.getElementById('modal-backdrop').classList.add('ease-in', 'duration-200', 'opacity-0');
                                document.getElementById('modal-panel').classList.add('ease-in', 'duration-200', 'opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                                document.getElementById('modal-backdrop').addEventListener('transitionend', function() {
                                    document.getElementById('default-modal').classList.add('hidden');
                                }, { once: true });
                            ">
                        Okay
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
