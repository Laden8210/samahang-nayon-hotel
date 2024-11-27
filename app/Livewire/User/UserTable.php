<?php

namespace App\Livewire\User;

use Database\Seeders\PersonalInformationTable;
use Livewire\Component;
use App\Models\PersonalInformation;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class UserTable extends Component
{

    public $search = '';

    public $user;

    public $deleteUserModal = false;
    public $viewUserModal = false;
    use WithPagination;

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {
        $current_user = Auth::user()->EmployeeId;
        return view('livewire.user.user-table', [
            'employees' => Employee::search($this->search)
                ->where('EmployeeId', '!=', $current_user)->paginate(20)
        ]);
    }

    public function changeStatus($id)
    {
        $user = Employee::where('EmployeeId', $id)->firstOrFail();
        if ($user->Status == 'Active') {
            $user->update([
                'Status' => 'Inactive'
            ]);
        } else {
            $user->update([
                'Status' => 'Active'
            ]);
        }
        session()->flash('message', 'Successfully ' . $user->Status . ' successfully!');
    }

    public function selectUser($id)
    {
        $this->user = Employee::where('EmployeeId', $id)->firstOrFail();
        $this->deleteUserModal = true;
    }

    public function viewUser($id)
    {
        $this->user = Employee::where('EmployeeId', $id)->firstOrFail();
        $this->viewUserModal = true;
    }

    public function deleteUser()
    {

        $this->user->delete();
        session()->flash('message', 'Successfully deleted!');
        $this->deleteUserModal = false;
    }

    public function cancelDelete()
    {
        $this->deleteUserModal = false;
    }
    public function cancelView()
    {
        $this->viewUserModal = false;
    }
}
