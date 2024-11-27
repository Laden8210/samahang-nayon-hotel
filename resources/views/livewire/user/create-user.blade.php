<div>
<form wire:submit.prevent="createUser">
@csrf
<div class="flex justify-between ">

<div class="p-2 bg-white rounded w-full shadow mx-2">
<h1 class="mx-2 font-bold">Create User</h1>
<div class="flex justify-between p-2 ">
<div class="border-b-4 border-cyan-600 w-auto px-10 flex justify-start p-1 align-middle">
<i class="far fa-check-circle text-cyan-500 mt-1 mx-2"></i>
<h4 class="text-cyan-500 font-bold">Profile Details</h4>
</div>

</div>

<div class="grid grid-cols-3 p-2 w-full">

<div class=" mx-2">

<x-text-field1 name="firstname" placeholder="Enter First Name" model="firstname"
    label="First Name" />
@error('firstname')
    <p class="text-red-500 text-xs italic mt-1"><i
            class="fas fa-exclamation-circle"></i></i>{{ $message }}
    </p>
@enderror

</div>

<div class="mx-2">

<x-text-field1 name="middlename" placeholder="Enter Middle Name" model="middlename"
    label="Middle Name" />
@error('middlename')
    <p class="text-red-500 text-xs italic mt-1"><i
            class="fas fa-exclamation-circle"></i></i>{{ $message }}
    </p>
@enderror

</div>

<div class=" mx-2">

<x-text-field1 name="lastname" placeholder="Enter Last Name" model="lastname"
    label="Last Name" />
@error('lastname')
    <p class="text-red-500 text-xs italic mt-1"><i
            class="fas fa-exclamation-circle"></i></i>{{ $message }}
    </p>
@enderror

</div>

</div>
<div class="grid grid-cols-3 p-2 w-full">

<div class=" mx-2">

<x-text-field1 name="contactNumber" placeholder="Enter Contact Number" model="contactNumber"
    label="Contact Number" />
@error('contactNumber')
    <p class="text-red-500 text-xs italic mt-1"><i
            class="fas fa-exclamation-circle"></i></i>{{ $message }}
    </p>
@enderror

</div>
<div class="mx-2">

<x-text-field1 name="email" placeholder="Enter Email Address" model="email"
    label="Email Address" />
@error('email')
    <p class="text-red-500 text-xs italic mt-1"><i
            class="fas fa-exclamation-circle"></i></i>{{ $message }}
    </p>
@enderror

</div>
<div class="mx-2">
<label
    class="block mb-2 mt-1 text-sm font-medium text-gray-900 dark:text-white">Province</label>
<select wire:model="selectedProvince" wire:change="fetchCities"
    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
    <option value="">Select Province</option>
    @foreach ($apiProvince as $region)
        <option value="{{ $region['code'] }}">
            {{ $region['name'] }}
        </option>
    @endforeach
</select>
@error('selectedProvince')
    <span class="text-red-600 text-sm">{{ $message }}</span>
@enderror
</div>
<div class="mx-2">
<label class="block mb-2 mt-1 text-sm font-medium text-gray-900 dark:text-white">City</label>
<select wire:model="selectedCity" wire:change="fetchBarangays"
    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
    <option value="">Select City</option>
    @foreach ($apiCity as $city)
        <option value="{{ $city['code'] }}">
            {{ $city['name'] }}
        </option>
    @endforeach
</select>
@error('selectedCity')
    <span class="text-red-600 text-sm">{{ $message }}</span>
@enderror
</div>

<div class="mx-2">
<label class="block mb-2 mt-1 text-sm font-medium text-gray-900 dark:text-white">Brgy</label>
<select wire:model="selectedBrgy"
    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
    <option value="">Select Barangay</option>
    @foreach ($apiBrgy as $brgy)
        <option value="{{ $brgy['code'] }}">
            {{ $brgy['name'] }}
        </option>
    @endforeach
</select>
@error('selectedBrgy')
    <span class="text-red-600 text-sm">{{ $message }}</span>
@enderror
</div>

<div class=" mx-2">

<x-text-field1 name="street" placeholder="Enter Street" model="street" label="Street" />
@error('street')
    <p class="text-red-500 text-xs italic mt-1"><i
            class="fas fa-exclamation-circle"></i></i>{{ $message }}
    </p>
@enderror

</div>
</div>
<div class="grid grid-cols-3 p-2 w-full">
<div class="mx-2">

<x-text-field1 name="dob" type="date" placeholder="Enter Birthdate" model="dob"
    label="Birhtdate" />
@error('dob')
    <p class="text-red-500 text-xs italic mt-1"><i
            class="fas fa-exclamation-circle"></i></i>{{ $message }}
    </p>
@enderror

</div>
<div class=" mx-2">
<x-combobox name="gender" model="gender" placeholder="Select Gender" :options="['Male', 'Female']" />

@error('gender')
    <p class="text-red-500 text-xs italic mt-1"><i
            class="fas fa-exclamation-circle"></i></i>{{ $message }}
    </p>
@enderror
</div>
<div class=" mx-2">
<x-combobox name="position" model="position" placeholder="Select Position" :options="['System Administrator', 'Manager', 'Receptionist']" />

@error('position')
    <p class="text-red-500 text-xs italic mt-1"><i
            class="fas fa-exclamation-circle"></i></i>{{ $message }}
    </p>
@enderror
</div>
</div>
<div class="flex items-end justify-end mx-2 mt-5">
<div class="flex gap-2">
<a href="{{ route('user') }}" class="bg-red-400 font-medium text-white px-2 py-1 rounded">
    Cancel
</a>
<button type="submit" class="bg-cyan-400 font-medium text-white px-2 py-1 rounded" x-data
    x-on:click="$dispatch('open-loader', {name: 'delete-modal'})">
    Create
</button>
</div>
</div>
</div>
@if (session()->has('message'))
<x-success-message-modal message="{{ session('message') }}" />
@endif
</form>
<div wire:loading>
<x-loader />

</div>
</div>
