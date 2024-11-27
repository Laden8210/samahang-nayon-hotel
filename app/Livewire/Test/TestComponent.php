<?php

namespace App\Livewire\Test;

use App\Models\Room;
use Livewire\Component;


class TestComponent extends Component
{
    public function render()
    {
        $rooms = Room::all();
        return view('livewire.test.test-component', ['rooms' => $rooms]);
    }
}
