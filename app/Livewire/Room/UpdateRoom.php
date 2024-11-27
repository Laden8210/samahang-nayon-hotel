<?php

namespace App\Livewire\Room;

use Livewire\Component;
use App\Models\Room;
use Livewire\WithFileUploads;

class UpdateRoom extends Component
{
    use WithFileUploads;

    public $roomId;
    public $rate;

    public $roomType;
    public $capacity;
    public $description;
    public $pictures = [];
    public $existingPictures;
    public $room;

    public function render()
    {
        return view('livewire.room.update-room');
    }

    public function mount($roomId)
    {
        $room = Room::with('roomPictures')->find($roomId);
        $this->room = $room;
        $this->rate = $room->RoomPrice;

        $this->roomType = $room->RoomType;
        $this->capacity = $room->Capacity;
        $this->description = $room->Description;
        $this->existingPictures = $room->roomPictures;
    }

    public function updateRoom()
    {
        $this->validate(
            [
                'rate' => 'required|min:100|max:10000',
                'roomType' => 'required|unique:rooms,RoomType,' . $this->roomId . ',RoomId', // Specify the primary key column
                'capacity' => 'required',
                'description' => 'required',
            ],
            [
                'rate.required' => 'The room rate field is required.',
                'rate.min' => 'The room rate field must be at least 100.',
                'rate.max' => 'The room rate field may not be greater than 10000.',
                'roomType.required' => 'The room type field is required.',
                'roomType.unique' => 'The room type must be unique.',
                'capacity.required' => 'The capacity field is required.',
                'description.required' => 'The description field is required.',
            ]
        );


        $room = Room::find($this->roomId);
        $room->RoomPrice = $this->rate;

        $room->RoomType = $this->roomType;
        $room->Capacity = $this->capacity;
        $room->Description = $this->description;


        $room->save();

        foreach ($this->pictures as $picture) {
            $room->roomPictures()->create([
                'PictureFile' => file_get_contents($picture->getRealPath()),
            ]);
        }

        session()->flash('message', 'Room updated successfully.');

        $this->rate = '';

        $this->roomType = '';
        $this->capacity = '';
        $this->description = '';
        $this->pictures = [];

        sleep(2);
        return redirect()->route('rooms');
    }

    public function removeExistingPicture($index)
    {

        if ($this->existingPictures->has($index)) {

            $picture = $this->existingPictures[$index];
            $picture->delete();


            $this->existingPictures->forget($index);

            session()->flash('message', 'Picture removed successfully.');
        } else {
            session()->flash('error', 'Picture not found.');
        }
    }

    public function removePicture($index)
    {
        array_splice($this->pictures, $index, 1);
    }
}
