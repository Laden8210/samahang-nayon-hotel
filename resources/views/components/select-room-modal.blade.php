@props(['body'])
@props(['name', 'title'])
<div
    x-data = "{ open: false, name: '{{ $name }}' }"
    x-show = "open"
    x-on:open-modal.window = "open = ($event.detail.name === name);console.log($event.detail.name)"
    x-on:close-modal.window = "open = false"
    x-on:keydown.escape.window = "open = false"
    style="display: none"
    class="fixed z-50 inset-0">
    <div x-on:click="open = false" class="fixed inset-0 bg-gray-300 opacity-40">
    </div>
    <div class="bg-white rounded m-auto fixed inset-0 max-w-7xl h-fit p-5">
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ $title ?? ""}}
            </h3>
            <button
            type="button"
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                 x-on:click="$dispatch('close-modal')">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
        </div>

        <div>
            {{ $body}}
        </div>
    </div>


</div>

