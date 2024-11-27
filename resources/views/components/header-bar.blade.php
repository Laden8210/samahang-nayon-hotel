
<nav class="flex drop-shadow h-auto bg-white p-3 justify-end z-40 relative">
    <div class="grid grid-cols-2 gap-2 items-center px-2 my-1">


        <div class="relative">
            <button data-dropdown-toggle="notificationDropdown" class="rounded-full w-6 h-6 overflow-hidden align-middle items-center mx-1">
                <i class="far fa-bell mx-1 text-slate-500"></i>
            </button>


            @if ($unreadCount > 0)
                <span class="absolute left-4 rounded-full bg-red-700 px-1.5 text-white font-semibold notification-count-badge"
                      style="font-size: 10px">{{ $unreadCount }}</span>
            @endif


            <div id="notificationDropdown"
                 class="z-50 absolute top-10 right-0 bg-white divide-y divide-gray-100 rounded-lg shadow-lg drop-shadow-sm w-64 p-2 dark:bg-gray-700 hidden">
                @forelse ($notifications as $notification)
                    <div class="p-2 rounded-lg hover:bg-slate-100 cursor-pointer"
                         data-notification-id="{{ $notification->id }}"
                         onclick="showFullMessage('{{ $notification->id }}', '{{ $notification->title }}', '{{ $notification->message }}')">
                        <p class="text-xs font-semibold truncate">{{ $notification->title }}</p>
                        <p class="text-xs truncate">{{ $notification->message }}</p>
                        <span class="text-xs text-slate-500">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <div class="p-2 rounded-lg">
                        <p class="text-xs text-center text-gray-500">No new notifications</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Profile Button -->
        <button class="rounded-full bg-slate-500 w-6 h-6 overflow-hidden align-middle items-center mx-1"
                id="dropdownDelayButton" data-dropdown-toggle="dropdownDelay" data-dropdown-delay="500">
            <img src="{{ asset('img/logo.jpg') }}" class="w-6">
        </button>

        <!-- Profile Dropdown -->
        <div id="dropdownDelay"
             class="z-50 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-lg w-44 dark:bg-gray-700 max-h-96 overflow-auto">
            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDelayButton">
                <li class="flex justify-normal items-center gap-2 w-full">
                    <a href="{{ route('logout') }}"
                       class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white w-full">
                        <i class="fas fa-sign-out-alt"></i> Log out</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Full Message Modal -->
<div id="fullMessageModal" class="fixed inset-0  flex items-center justify-center  z-50 hidden">
    <div class="bg-white rounded-lg w-80 p-4 shadow-lg z-70">
        <h3 id="modalTitle" class="text-lg font-bold mb-2"></h3>
        <p id="modalMessage" class="text-sm text-gray-600"></p>
        <button onclick="closeFullMessageModal()" class="mt-4 bg-blue-500 text-white py-1 px-4 rounded-lg">Close</button>
    </div>
</div>

<script>
    // Show Full Message Modal and Mark Notification as Read
    function showFullMessage(id, title, message) {
        // Update modal content
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalMessage').textContent = message;
        document.getElementById('fullMessageModal').classList.remove('hidden');

        // AJAX request to update notification status to 'read'
        fetch(`/notifications/${id}/mark-as-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                // Update the notification count and UI
                document.querySelector(`[data-notification-id="${id}"]`).remove();
                const unreadBadge = document.querySelector('.notification-count-badge');
                if (unreadBadge) {
                    let unreadCount = parseInt(unreadBadge.textContent);
                    unreadBadge.textContent = unreadCount > 1 ? unreadCount - 1 : '';
                }
            }
        });
    }

    // Close Full Message Modal
    function closeFullMessageModal() {
        document.getElementById('fullMessageModal').classList.add('hidden');
    }

    // Toggle Notification Dropdown
    document.querySelector('[data-dropdown-toggle="notificationDropdown"]').addEventListener('click', function () {
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.classList.toggle('hidden');
    });
</script>
