<div>
    <x-modal name="change-passsword" title="Change Password">

        @slot('body')
            <form wire:submit.prevent="changePassword">
                <div class="px-2">

                    <x-text-field1 name="newPassword" placeholder="Enter new Password" model="newPassword" label="New Password"
                        type="text" />

                    <x-text-field1 name="confirmPassword" placeholder="Enter confirm Password" model="confirmPassword"
                        label="Enter confirm Password" type="text" />


                    <x-text-field1 name="oldPassword" placeholder="Enter old Password" model="currentPassword"
                        label="Enter old Password" type="text" />


                    <button type="submit"
                        class="mt-5 text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Change Password
                    </button>

                </div>
            </form>
        @endslot
    </x-modal>



    @if (session()->has('message'))
        <x-success-message-modal message="{{ session('message') }}" />
    @endif
    @if (session()->has('error'))
        <x-success-message-modal message="{{ session('error') }}" />
    @endif

</div>
