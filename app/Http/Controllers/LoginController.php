<?php

namespace App\Http\Controllers;


use App\Models\Employee;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request as FacadesRequest;
use PHPUnit\Event\Telemetry\System;
use Illuminate\Support\Facades\Crypt;

class LoginController extends Controller
{
    public function index()
    {

        if (Auth::check()) {
            return redirect('admin/');
        }
        return view('index');
    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $employee = Employee::where('email', $request->email)->first();



        if (!$employee) {
            SystemLog::create([
                'log' => 'Login failed from IP: ' . FacadesRequest::ip() . ' for email: ' . $request->email,
                'action' => 'Login Failed',

                'date_created' => date('Y-m-d')
            ]);
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records',
            ])->withInput($request->only('email'));
        }





        if (!Hash::check($request->password, $employee->password)) {
            SystemLog::create([
                'log' => 'Login failed from IP: ' . FacadesRequest::ip() . ' for email: ' . $request->email . ' - Reason: Incorrect password',
                'action' => 'Login Failed',
                'date_created' => now()->toDateString(),
            ]);
            return back()->withErrors([
                'email' => 'The provided password is incorrect',
            ])->withInput($request->only('email'));
        }

        if ($employee->Status == 'Inactive') {
            SystemLog::create([
                'log' => 'Login failed from IP: ' . FacadesRequest::ip() . ' for email: ' . $request->email . ' - Reason: Account inactive',
                'action' => 'Login Failed',
                'date_created' => now()->toDateString(),
            ]);
            return back()->withErrors([
                'email' => 'Your account is inactive. Please contact the administrator.',
            ])->withInput($request->only('email'));
        }


        if ($employee->is_verified == 0) {
            SystemLog::create([
                'log' => 'Login failed from IP: ' . FacadesRequest::ip() . ' for email: ' . $request->email . ' - Reason: Account not verified',
                'action' => 'Login Failed',
                'date_created' => now()->toDateString(),
            ]);
            return back()->withErrors([
                'email' => 'Your account is not verified. Please check your email for verification link.',
            ])->withInput($request->only('email'));
        }

        if ($employee->verification_token != '') {
            return redirect()->route('verify-user', ['token' => $employee->verification_token]);
        }

        if ($employee->is_password_change_required == 1) {
            $encryptedId = Crypt::encrypt($employee->EmployeeId);
            return redirect()->route('change-password', ['id' => $encryptedId]);
        }


        Auth::login($employee);

        $request->session()->regenerate();


        if (Auth::user()->Position == 'System Administrator') {
            SystemLog::create([
                'log' => 'System Administrator logged in from IP: ' . FacadesRequest::ip() . ' for email: ' . $request->email,
                'action' => 'Login',
                'date_created' => date('Y-m-d'),  // More idiomatic date handling
            ]);
            return redirect()->intended('admin/');
        } elseif (Auth::user()->Position == 'Receptionist') {
            SystemLog::create([
                'log' => 'Receptionist logged in from IP: ' . FacadesRequest::ip() . ' for email: ' . $request->email,
                'action' => 'Login',
                'date_created' => date('Y-m-d'),
            ]);
            return redirect()->intended('admin');
        } elseif (Auth::user()->Position == 'Manager') {
            SystemLog::create([
                'log' => 'Manager logged in from IP: ' . FacadesRequest::ip() . ' for email: ' . $request->email,
                'action' => 'Login',
                'date_created' => date('Y-m-d'),
            ]);
            return redirect()->intended('admin');
        }


        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {

        Auth::logout();
        session()->invalidate();

        return redirect('/');
    }

    public function verifyUser(Request $request)
    {
        $token = $request->token;


        $employee = Employee::where('verification_token', $token)->first();

        if ($employee) {
            $employee->is_verified = 1;
            $employee->verification_token = '';
            $employee->save();

            return redirect()->route('index')->with('message', 'Account verified successfully. You can now login.');
        } else {
            return redirect()->route('index')->with('message', 'Invalid verification token.');
        }
    }


    public function changePassword($id)
    {
        $employeeId = Crypt::decrypt($id);
        $employee = Employee::find($employeeId);

        return view('change-password.index', compact('employee'));
    }

    public function updatePassword(Request $request)
    {

        $request->validate([
            'token' => 'required',
            'password' => [
                'required',
                'min:8',
                'regex:/[a-zA-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&_.]/',
            ],
            'confirm_password' => 'required|same:password',
        ]);

        $employeeId = Crypt::decrypt($request->token);

        $employee = Employee::find($employeeId);


        $employee->update([
            'password' => bcrypt($request->password),
        ]);

        $employee->is_password_change_required = 0;
        $employee->save();

        return redirect()->route('index')->with('message', 'Password updated successfully. You can now login.');
    }
}
