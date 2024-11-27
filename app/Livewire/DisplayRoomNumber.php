<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Room;
use App\Models\RoomNumber;

class DisplayRoomNumber extends Component
{

    public $displayRoomNumberModal = false;
    public $deleteRoomModal = false;
    public $roomNumber;

    public $selectedRoom;


    public function render()
    {

        $rooms = Room::all();

        return view(
            'livewire.display-room-number',
            [
                'rooms' => $rooms,
                'roomNumbers' =>  RoomNumber::withoutTrashed()->get()
            ]
        );
    }

    public function viewModal($roomNumber)
    {
        $this->displayRoomNumberModal = true;
        $this->roomNumber = $roomNumber;
    }

    public function closeModal()
    {
        $this->displayRoomNumberModal = false;
    }
    public function saveRoom()
    {
        $this->validate([
            'selectedRoom' => 'required'
        ]);

        $existingRoom = RoomNumber::where('room_number', $this->roomNumber)
            ->where('RoomId', $this->selectedRoom)->first();

        if ($existingRoom) {

            session()->flash('error', 'This room number already exists.');
            return;
        }

        RoomNumber::create([
            'RoomId' => $this->selectedRoom,
            'room_number' => $this->roomNumber
        ]);

        // Optionally, you can set a success message
        session()->flash('success', 'Room number saved successfully.');
    }

    public function deleteRoom($id)
    {
        $this->deleteRoomModal = true;
        $this->selectedRoom = $id;

    }

    public function confirmDelete()
    {
        RoomNumber::where('room_number_id', $this->selectedRoom)->delete();
        $this->deleteRoomModal = false;
        session()->flash('success', 'Room number deleted successfully.');
    }
}
