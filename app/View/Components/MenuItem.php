<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MenuItem extends Component
{
    public $title;
    public $url;
    public $icon;
    public $active;
    public $badge;
    public $badgeCount;

    public function __construct($title, $url, $icon, $active = false, $badge = false, $badgeCount = 0)
    {
        $this->title = $title;
        $this->url = $url;
        $this->icon = $icon;
        $this->active = $active;
        $this->badge = $badge;
        $this->badgeCount = $badgeCount;
    }

    public function render(): View|Closure|string
    {
        return view('components.menu-item');
    }
}
