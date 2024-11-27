<?php

namespace App\Livewire\Booking;

use Livewire\Component;

use App\Models\Reservation;
use Livewire\WithPagination;

class BookingTable extends Component
{

    use WithPagination;


    public $search = '';

    public $status;
    public function render()
    {
        return view('livewire.booking.booking-table', [
            'bookings' => Reservation::when($this->search, fn($query) => $query->search($this->search))
                ->when($this->status, fn($query) => $query->where('Status', $this->status))
                ->orderBy('ReservationId', 'desc')->paginate(10),
            'statuses' => Reservation::select('Status')->distinct()->get()
        ]);
    }
}
