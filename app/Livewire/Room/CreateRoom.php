<?php

namespace App\Livewire\Room;


use Livewire\Component;
use App\Models\Room;
use Livewire\WithFileUploads;
use App\Models\RoomPictures;

class CreateRoom extends Component
{
    use WithFileUploads;
    public $rate;

    public $roomType;
    public $capacity;
    public $description;
    public $pictures = [];



    public function render()
    {
        return view('livewire.room.create-room');
    }

    public function createRoom()
    {
        $this->validate(
            [
                'rate' => 'required|numeric|min:100|max:10000',

                'roomType' => 'required|unique:rooms,RoomType',
                'capacity' => 'required',
                'description' => 'required',
            ],
            [
                'rate.required' => 'The room rate field is required.',
                'rate.numeric' => 'The room rate field must be numeric.',
                'rate.min' => 'The room rate field must be at least 100.',
                'rate.max' => 'The room rate field may not be greater than 10000.',

                'roomType.required' => 'The room type field is required.',
                'roomType.unique' => 'The room type must be unique.',
                'capacity.required' => 'The capacity field is required.',
                'description.required' => 'The description field is required.',

            ]

        );

        $room = new Room();
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

        session()->flash('message', 'Room created successfully.');

        $this->rate = '';

        $this->roomType = '';
        $this->capacity = '';
        $this->description = '';

        sleep(1);
        return redirect()->route('rooms');
    }

    public function removePicture($index)
    {
        array_splice($this->pictures, $index, 1);
    }
}
