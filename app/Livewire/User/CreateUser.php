<?php

namespace App\Livewire\User;

use App\Models\Employee;
use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Models\UserAccount;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Rules\Age;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmployee;

class CreateUser extends Component
{

    public $firstname;
    public $middlename;
    public $lastname;
    public $contactNumber;
    public $email;
    public $street;
    public $city;
    public $province;
    public $dob;
    public $gender;
    public $position;
    public $brgy;

    public $apiProvince = [];
    public $apiCity = [];
    public $apiBrgy = [];
    public $selectedProvince = null;
    public $selectedCity = null;

    public $selectedBrgy = null;

    public $isLoaderShown = false;

    public function createUser()
    {
        $this->validate(
            [
                'firstname' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[\p{L} .-]+$/u'
                ],
                'middlename' => [
                    'nullable',
                    'string',
                    'max:255',
                    'regex:/^[\p{L} .-]+$/u'
                ],
                'lastname' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[\p{L} .-]+$/u'
                ],
                'contactNumber' => [
                    'required',
                    'string',
                    'max:12',
                    'regex:/^(?:\+63|0)9\d{9}$/',
                    'unique:employees,ContactNumber',
                ],
                'email' => 'required|email|unique:employees,email',
                'street' => 'required|string|max:255',
                'selectedProvince' => 'required',
                'selectedCity' => 'required',
                'selectedBrgy' => 'required',
                'dob' => ['required', 'date', new Age],
                'gender' => ['required', Rule::in(['Male', 'Female'])],
                'position' => ['required', Rule::in(['System Administrator', 'Manager', 'Receptionist'])],
            ]
        );

        $birthdate = new \DateTime($this->dob);
        $month = $birthdate->format('m');
        $day = $birthdate->format('d');
        $year = $birthdate->format('Y');

        $defaultPassword = Str::lower($this->lastname) . $month . $day . $year;

        $province = "";
        $city = "";
        $brgy = "";

        foreach ($this->apiProvince as $prov) {
            if ($prov['code'] == $this->selectedProvince) {
                $province = $prov['name'];
                break;
            }
        }
        foreach ($this->apiCity as $cit) {
            if ($cit['code'] == $this->selectedCity) {
                $city = $cit['name'];
                break;
            }
        }
        foreach ($this->apiBrgy as $b) {
            if ($b['code'] == $this->selectedBrgy) {
                $brgy = $b['name'];
                break;
            }
        }

        $employee = Employee::create([
            'FirstName' => $this->firstname,
            'MiddleName' => $this->middlename,
            'LastName' => $this->lastname,
            'ContactNumber' => $this->contactNumber,
            'email' => $this->email,
            'Street' => $this->street,
            'Brgy' => $brgy,
            'City' => $city,
            'Province' => $province,
            'Birthdate' => $this->dob,
            'Gender' => $this->gender,
            'Position' => $this->position,
            'Status' => 'Active',
            'Username' => $this->email,
            'email' => $this->email,
            'password' => bcrypt($defaultPassword),
            'DateCreated' => now()->format('Y-m-d'),
            'TimeCreated' => now()->format('H:i:s'),
            'is_verified' => 0,
            'verification_token' => Str::random(32),
        ]);

        Mail::to($this->email)->send(new VerifyEmployee($employee, $defaultPassword));

        session()->flash('message', 'User created successfully!');
        $this->isLoaderShown = false;
        $this->reset();
    }
    public function fetchRegions()
    {
        $this->apiProvince = collect(Http::get('https://psgc.gitlab.io/api/provinces/')->json())
            ->sortBy('name')
            ->values()
            ->toArray();
    }
    public function fetchCities()
    {
        if ($this->selectedProvince) {
            $this->apiCity = collect(Http::get("https://psgc.gitlab.io/api/provinces/{$this->selectedProvince}/cities-municipalities/")->json())
                ->sortBy('name')
                ->values()
                ->toArray();
        } else {
            $this->apiCity = [];
        }
    }
    public function fetchBarangays()
    {
        if ($this->selectedCity) {
            $this->apiBrgy = collect(Http::get("https://psgc.gitlab.io/api/cities-municipalities/{$this->selectedCity}/barangays/")->json())
                ->sortBy('name')
                ->values()
                ->toArray();
        } else {
            $this->apiBrgy = [];
        }
    }
    public function render()
    {
        $this->fetchRegions();
        return view('livewire.user.create-user');
    }
}
