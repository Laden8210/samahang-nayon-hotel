<?php

namespace App\Livewire\Room;

use Livewire\Component;
use App\Models\Room;

use Livewire\WithPagination;

class RoomTable extends Component
{
    public $search = '';
    protected $listeners = ['refreshComponent' => '$refresh'];

    public $deleteRoomModal = false;
    use WithPagination;

    public $selectedRoom;

    public function render()
    {
        return view(
            'livewire.room.room-table',
            [
                'rooms' => Room::search($this->search)->get()
            ]
        );
    }


    public function placeholder()
    {
        return view('placeholder.room');
    }

    public function delete($id)
    {
        Room::destroy($id);
        session()->flash('message', 'Room deleted.');
        $this->render();
    }

    public function confirmDelete($id)
    {
        $this->deleteRoomModal = true;
        $this->selectedRoom = Room::findOrFail($id);
    }

    public function cancelDelete()
    {
        $this->deleteRoomModal = false;
    }

    public function deleteRoom()
    {
        $room = $this->selectedRoom->load('roomNumber');


        foreach ($room->roomNumber as $roomNumber) {
            $roomNumber->delete();
        }

        $room->delete();

        $this->deleteRoomModal = false;


        session()->flash('message', 'Room deleted.');
    }
}
