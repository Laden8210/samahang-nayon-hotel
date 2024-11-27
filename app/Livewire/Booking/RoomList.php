<?php

namespace App\Livewire\Booking;

use App\Models\Reservation;
use Livewire\Component;
use App\Models\Room;
use App\Models\RoomNumber;
use App\Models\Promotion;
use Carbon\Carbon;

class RoomList extends Component
{

    public $date;



    public function mount()
    {

        $this->date = Carbon::today()->format('Y-m-d');
    }
    public function render()
    {



        $roomNumbers = RoomNumber::all();


        $promotion = Promotion::where('StartDate', '<=', $this->date)
            ->where('EndDate', '>=', $this->date)
            ->with('discountedRooms')
            ->first();


        if ($promotion && $promotion->discountedRooms) {
            foreach ($roomNumbers as $roomNumber) {
                foreach ($promotion->discountedRooms as $discountedRoom) {
                    if ($discountedRoom->RoomId == $roomNumber->RoomId) {
                        $roomNumber->discount = $promotion->Discount;
                    }
                }
            }
        }

        $reservation = Reservation::where('DateCheckIn', '<=', $this->date)
            ->where('DateCheckOut', '>=', $this->date)
            ->get();

        foreach ($roomNumbers as $roomNumber) {
            $roomNumber->isBooked = false;
            foreach ($reservation as $res) {
                if ($res->room_number_id == $roomNumber->room_number_id) {
                    $roomNumber->isBooked = true;
                }
            }
        }

        return view('livewire.booking.room-list', [
            'roomNumbers' => $roomNumbers
        ]);
    }
}
