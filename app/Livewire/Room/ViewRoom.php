<?php

namespace App\Livewire\Room;

use Livewire\Component;
use App\Models\Room;

class ViewRoom extends Component
{

    public $room;
    public $pictures = [];
    public $topImage;
    public function render()
    {
        return view('livewire.room.view-room');
    }

    public function mount($roomId){
        $room = Room::find($roomId);

        $this->room = $room;
        $this->pictures = $room->roomPictures;
        $this->topImage = $room->roomPictures->first();
    }
}
