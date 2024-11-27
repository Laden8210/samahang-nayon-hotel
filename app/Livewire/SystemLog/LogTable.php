<?php

namespace App\Livewire\SystemLog;

use Livewire\Component;

use App\Models\SystemLog;

class LogTable extends Component
{

    public $search = '';
    public function render()
    {
        return view(
            'livewire.system-log.log-table',
            [
                'logs' => SystemLog::search($this->search)->orderBy('created_at', 'desc')->get()
            ]
        );
    }
}
