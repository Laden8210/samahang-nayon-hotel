<?php

namespace App\Livewire\CheckInOut;

use Livewire\Component;
use App\Models\CheckInOut;
use Livewire\WithPagination;

class CheckInOutTable extends Component
{
    use WithPagination;

    public $search = '';
    public function render()
    {
        return view('livewire.check-in-out.check-in-out-table', [
            'checkInOuts' => CheckInOut::with('reservation', 'guest')
            ->search($this->search)
                ->where('Type', 'Checked In')
                ->orderBy('DateCreated', 'desc')
                ->orderBy('TimeCreated', 'desc')
                ->paginate(10)
        ]);
    }
}
