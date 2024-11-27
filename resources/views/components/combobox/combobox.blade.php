<div class="mt-1">

    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{$placeholder}} </label>
    <select name="{{ $name }}"
        wire:model="{{ $model }}"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
        <option value=""> {{ $placeholder }}</option>
        @foreach($options as $option)
            <option value="{{ $option }}">{{ $option }}</option>
        @endforeach
    </select>
</div>
