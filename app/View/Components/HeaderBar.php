<?php

namespace App\View\Components;

use App\Models\Notification;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HeaderBar extends Component
{
    public $unreadCount;
    public $notifications;


    public function __construct()
    {

        $this->notifications = Notification::where('status', 'unread')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $this->unreadCount = $this->notifications->count();
    }

    public function render(): View|Closure|string
    {
        return view('components.header-bar', [
            'unreadCount' => $this->unreadCount,
            'notifications' => $this->notifications,
        ]);
    }
}
