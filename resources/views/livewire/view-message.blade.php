<div class="w-full h-screen shadow-lg rounded-lg overflow-hidden z-0">
    <div class="grid grid-cols-12 h-full">
        <!-- Sidebar: Contact List -->
        <div class="col-span-3 bg-gray-100 p-4 border-r border-gray-300 overflow-auto">
            <!-- Search bar -->
            <div class="flex justify-between items-center mb-4 gap-2">
                <input type="text" class="w-full p-2 border border-gray-300 rounded-full" placeholder="Search"
                    wire:model.live="search">
            </div>

            <div class="text-xl font-semibold mb-4 text-gray-700">All Messages</div>

            <!-- Contacts List -->
            <div class="space-y-3">
                @foreach ($guests as $guest)
                    <div class="relative p-3 bg-white rounded-lg shadow-sm cursor-pointer hover:bg-gray-200">
                        <button class="flex flex-col w-full text-left" wire:click="selectGuest({{ $guest->GuestId }})">
                            <div class="flex justify-between items-center w-full">
                                <div class="flex items-center gap-3">

                                    <div>
                                        <h3 class="font-semibold text-gray-900">
                                            {{ $guest->FirstName . ' ' . $guest->LastName }}
                                        </h3>
                                        <!-- Latest message preview and time -->
                                        <div class="flex items-center justify-between text-sm text-gray-600 w-full">
                                            <p class="truncate w-40">
                                                @if ($guest->messages->isNotEmpty())
                                                    @php
                                                        $latestMessage = $guest->messages->first();
                                                    @endphp
                                                    {{ $latestMessage->Message }}
                                                @else
                                                    <span class="italic text-gray-400">No messages yet</span>
                                                @endif
                                            </p>
                                            <span class="text-xs text-gray-400">
                                                @if (isset($latestMessage))
                                                    {{ \Carbon\Carbon::parse($latestMessage->TimeSent)->format('h:i A') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Unread notification with count -->
                                @php
                                    $unreadCount = $guest->messages->where('IsReadEmployee', false)->count();
                                @endphp
                                @if ($unreadCount > 0)
                                    <span class="bg-red-500 text-white text-xs font-bold rounded-full px-2 py-1 ml-2">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </div>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Chat Area -->
        <div class="col-span-9 bg-white flex flex-col h-full">
            <!-- Chat Header -->
            <div class="p-4 bg-gray-200 border-b border-gray-300 flex items-center gap-4">
                <div class="rounded-full w-12 h-12 flex-shrink-0"></div>
                <h1 class="text-lg font-bold text-gray-800">
                    @if ($selectedGuest)
                        {{ $selectedGuest->FirstName . ' ' . $selectedGuest->LastName }}
                    @endif
                </h1>
            </div>

            <!-- Messages Area with fixed height and scrolling -->
            <div class="flex-1 overflow-y-auto p-4" wire:poll.debounce.1000ms
                style="background-color: #f0f2f5; max-height: calc(100vh - 160px);">
                @if ($selectedGuest)
                    @foreach ($selectedGuest->messages as $message)
                        <div class="mb-3 flex {{ $message->isGuestMessage === 0 ? 'justify-end' : 'justify-start' }}">
                            <div
                                class="rounded-lg p-3 max-w-xs {{ $message->isGuestMessage === 1 ? 'bg-white shadow' : 'bg-blue-500 text-white' }}">
                                <p class="text-sm">{{ $message->Message }}</p>
                                <span
                                    class="text-xs {{ $message->isGuestMessage === 1 ? 'text-gray-500' : 'text-blue-100' }}">
                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $message->TimeSent)->format('h:i A') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Message Input -->
            <div class="border-t border-gray-300 p-4 bg-gray-100 sticky bottom-0">
                <form wire:submit.prevent="sendMessage" class="flex items-center gap-2">
                    <textarea
                        class="w-full p-3 border border-gray-300 rounded-full resize-none focus:outline-none focus:ring-2 focus:ring-blue-400"
                        rows="1" wire:model="message" placeholder="Type a message..."></textarea>
                    <button type="submit"
                        class="bg-blue-500 text-white px-5 py-2 rounded-full font-semibold">Send</button>
                </form>
            </div>
        </div>
    </div>
</div>
