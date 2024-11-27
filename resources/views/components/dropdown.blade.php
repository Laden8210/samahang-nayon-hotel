@props(['body'])
@props(['name', 'title'])
<div
    x-data = "{ openDropdown: false, name: '{{ $name }}' }"
    x-show = "openDropdown"
    x-on:open-dropdown.window = "openDropdown = ($event.detail.name === name); console.log($event.detail.name)"
    x-on:close-dropdown.window = "openDropdown = false"
    x-on:keydown.escape.window = "openDropdown = false"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    aria-labelledby="dropdownDefaultButton"

    class="z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 relative m-auto">

    <div class="py-2 text-sm text-gray-700 dark:text-gray-200">
        {{ $body}}
    </div>
</div>

