<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\UserAccount;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.user.index');
    }

    public function addUser()
    {
        return view('admin.user.add');
    }

    public function updateUser($userId){

        $employee = Employee::where('EmployeeId', $userId)->firstOrFail();
        return view('admin.user.update', compact('employee'));

    }

    public function settings()
    {

        $user = Auth::user();
        return view('admin.user.setting', compact('user'));
    }
}
