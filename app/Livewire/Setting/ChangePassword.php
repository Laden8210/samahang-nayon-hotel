<?php

namespace App\Livewire\Setting;

use App\Models\Employee;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Request as FacadesRequest;
class ChangePassword extends Component
{
    public $currentPassword;
    public $newPassword;
    public $confirmPassword;

    private $currentUser;



    protected $rules = [
        'currentPassword' => 'required',
        'newPassword' => 'required|min:8|confirmed',
    ];

    public function render()
    {



        return view('livewire.setting.change-password');
    }

    public function changePassword()
    {

        $this->currentUser = Auth::user();

        if (!Hash::check($this->currentPassword, $this->currentUser->password)) {
            session()->flash('error', 'Current password is incorrect.');
            return;
        }


        $employee = Employee::find(  $this->currentUser->EmployeeId);

        $employee->password = bcrypt($this->newPassword);

        $employee->save();

        SystemLog::create([
            'log' => 'Password changed successfully from IP: ' . FacadesRequest::ip() .
                     ' for email: ' . $this->currentUser->email . // Use the current user's email
                     ' on ' . date('Y-m-d H:i:s'),
            'action' => 'Change Password', // Update action name
            'date_created' => date('Y-m-d'),
        ]);

        session()->flash('message', 'Password changed successfully.');

        // Reset the input fields
        $this->reset(['currentPassword', 'newPassword', 'confirmPassword']);
    }
}
