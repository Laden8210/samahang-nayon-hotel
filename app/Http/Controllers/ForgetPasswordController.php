<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequestOtp;

class ForgetPasswordController extends Controller
{

    public function index()
    {
        return view('forget-password.index');
    }

    public function resetPassword()
    {

        if (!session('reset-password')) {
            return redirect()->route('forget-password')->with('error', 'Please request OTP first.');
        }

        return view('forget-password.reset-password');
    }


    public function confirmChangePassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'min:8',
                'regex:/[a-zA-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&_.]/',
            ],
            'confirm_password' => 'required|same:password',
        ], [
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.regex' => 'The password must include at least one letter, one number, and one special character.',
            'confirm_password.required' => 'The confirm password field is required.',
            'confirm_password.same' => 'The confirm password must match the password.',
        ]);

        $token = $request->token;

        if (!session('reset-password')) {
            return redirect()->route('forget-password')->with('error', 'Please request OTP first.');
        }

        $employee = Employee::find(session('employeeId'));

        if (!$employee) {

            return redirect()->back()->with('error', 'Employee not found');
        }

        $employee->update([
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('password-changed')->with('success', 'Password changed successfully.');
    }


    public function passwordChanged()
    {
        return view('forget-password.changed-password');
    }

    public function requestOtp(Request $request)
    {
        $request->validate([
            'phoneEmail' => 'required',
        ]);

        $phoneEmail = $request->phoneEmail;
        $otp = rand(1000, 9999);



        $employee = Employee::where('ContactNumber', $phoneEmail)->orWhere('email', $phoneEmail)->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Phone number or email not found.');
        }

        if ($employee->email == $phoneEmail) {
            try {

                Mail::to($employee->email)->send(new RequestOtp($otp, $employee));

                session([
                    'otp' => $otp,
                    'employeeId' => $employee->EmployeeId,
                ]);
                return redirect()->route('enter-otp')->with('success', 'OTP sent successfully.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
            }
        }


        if ($employee->ContactNumber == $phoneEmail) {
            $response = Http::post('https://nasa-ph.com/api/send-sms', [
                'phone_number' => $phoneEmail,
                'message' => "Your OTP code is <strong>$otp</strong>. Use this code to reset your password. Please do not share this code with anyone. If you didn't request this, please ignore this message.",

            ]);


            if ($response->successful()) {
                session([
                    'otp' => $otp,
                    'employeeId' => $employee->EmployeeId,
                ]);

                return redirect()->route('enter-otp')->with('success', 'OTP sent successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
            }
        }
    }


    public function enterOtp()
    {
        return view('forget-password.otp');
    }

    public function confirmOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required',
        ]);

        $otp = $request->otp;



        if ($otp == session('otp')) {
            session()->forget('otp');
            session()->put('reset-password', true);
            return redirect()->route('reset-password');
        } else {
            return redirect()->back()->with('error', 'Invalid OTP. Please try again.');
        }
    }
}
