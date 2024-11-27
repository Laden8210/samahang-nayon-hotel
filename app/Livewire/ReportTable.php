<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Report;
use Illuminate\Support\Carbon;
use App\Models\Guest;

class ReportTable extends Component
{

    public $type;
    public $startdate;
    public $enddate;
    public $isEndDateDisabled = false;
    public $showGuest = false;

    public $search = '';

    public $guest;

    public function render()
    {
        return view('livewire.report-table', [
            'reports' => Report::search($this->search)->orderBy('ReportId', 'desc')->get(),
            'guests' => Guest::orderBy('FirstName')->orderBy('LastName')->get()
        ]);
    }
    public function createReport()
    {
        if ($this->type === 'Daily Revenue Report') {
            $this->validate([
                'type' => 'required|string',
                'startdate' => 'required|date|before_or_equal:today',
            ]);
        } else if ($this->type === 'Weekly Revenue Report') {
            $this->validate([
                'type' => 'required|string',
                'startdate' => 'required|date|before_or_equal:today',
            ]);
        } else if ($this->type === 'Monthly Revenue Report') {
            $this->validate([
                'type' => 'required|string',
                'startdate' => 'required|date|before_or_equal:today',
            ]);
        } else if ($this->type === 'Guest History Report') {


            $this->validate([
                'type' => 'required|string',
                'startdate' => 'required|date',
                'enddate' => 'nullable|date|before_or_equal:today|after:startdate',
            ]);
        } else {
            $this->validate([
                'type' => 'required|string',
                'startdate' => 'required|date',
                'enddate' => 'required|date',
            ]);
        }
        $employeeId = auth()->id();

        $user = auth()->user();
        $report = new Report();
        $report->ReportName = $this->type;

        $report->type = $this->type;
        $report->EmployeeId = $employeeId;
        $report->Date = $this->startdate;

        if ($this->type == 'Weekly Revenue Report') {
            $report->EndDate = Carbon::parse($this->startdate)->addWeek();
        } else

        if ($this->type == 'Monthly Revenue Report') {
            $report->EndDate = Carbon::parse($this->startdate)->addMonth();
        } else

        if ($this->type == 'Daily Revenue Report') {
            $report->EndDate = Carbon::parse($this->startdate)->addDay();
        } else if ($this->type == 'Guest History Report') {
            $report->EndDate = $this->enddate;
            $report->GuestId = $this->guest;
        } else {
            $report->EndDate = $this->enddate;
        }

        $report->CreatedAt = now();
        $report->save();
    }

    public function disableField()
    {
        $this->isEndDateDisabled = str_contains($this->type, 'Revenue Report');
    }
}
