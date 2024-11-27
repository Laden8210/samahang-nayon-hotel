<?php

namespace App\Livewire\Booking;

use App\Mail\GuestBooking;
use App\Mail\SamahangNayonMailer;
use App\Models\Amenities;
use App\Models\DiscountedRoom;
use Livewire\Component;
use App\Models\Guest;
use App\Models\Promotion;
use App\Models\Room;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\RoomNumber;

class CreateBooking extends Component
{
    public $firstname;
    public $lastname;
    public $middlename;
    public $dob;
    public $gender;
    public $email;
    public $contactnumber;
    public $street;
    public $brgy;
    public $city;
    public $province;

    public $selectedRoom;
    public $selectedGuestId;
    public $selectedRoomId;
    public $lengthOfStay;

    public $checkIn;
    public $checkOut;
    public $totalGuests;

    public $totalChildren;

    public $selectedAmenities = [];
    public $quantity = [];

    public $total;

    public $totalAmenitiesCost;

    public $discountedRoomRate;
    public $paymentAmount;
    public $paymentType;

    public $roomNumbers;

    public $discount;

    public $searchAmenity;

    public $subguests = [];


    public $subguestsFirstname;
    public $subguestsMiddlename;
    public $subguestsLastname;
    public $subguestsDob;
    public $subguestsGender;

    public $subguestsContactnumber;

    public $discountType;

    public $searchCustomer;


    public $apiProvince = [];
    public $apiCity = [];
    public $apiBrgy = [];
    public $selectedProvince = null;
    public $selectedCity = null;

    public $selectedBrgy = null;

    public $selectedRoomNumberId;

    public $idNumber;
    public function mount()
    {
        $this->checkIn = Carbon::today()->format('Y-m-d');
        $this->checkOut = Carbon::today()->addDay()->format('Y-m-d');
        $this->roomNumbers = $this->getAvailableRooms();
        $this->totalGuests = 1;
        $this->totalChildren = 0;
        $this->fetchRegions();
    }

    public function fetchRegions()
    {
        $this->apiProvince = Http::get('https://psgc.gitlab.io/api/provinces/')->json();
    }

    public function fetchCities()
    {
        if ($this->selectedProvince) {

            $this->apiCity = Http::get("https://psgc.gitlab.io/api/provinces/{$this->selectedProvince}/cities-municipalities/")->json();
        } else {
            $this->apiCity = [];
        }
    }

    public function fetchBarangays()
    {
        if ($this->selectedCity) {

            $this->apiBrgy = Http::get("https://psgc.gitlab.io/api/cities-municipalities/{$this->selectedCity}/barangays/")->json();
        } else {
            $this->apiBrgy = [];
        }
    }

    public function render()
    {
        return view('livewire.booking.create-booking', [
            'guests' => Guest::search($this->searchCustomer)->get(),

            'amenities' => Amenities::search($this->searchAmenity)->get(),
        ]);
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'checkIn') {

            $this->adjustCheckOutDate();
        }

        if (in_array($propertyName, ['checkIn', 'checkOut', 'totalChildren', 'totalGuests'])) {
            $this->roomNumbers = $this->getAvailableRooms();
        }

        if ($propertyName === 'discountType') {
            $this->applyDiscount();
        }
    }

    /**
     * Adjust the checkOut date to maintain the same duration.
     */
    private function adjustCheckOutDate()
    {
        if ($this->checkIn) {
            // Parse the checkIn date
            $checkInDate = Carbon::parse($this->checkIn);

            // Adjust the checkOut date to be one day after the new checkIn date
            $this->checkOut = $checkInDate->addDay()->toDateString();
        }
    }


    public function addSubGuest()
    {
        // Validation rules
        $this->validate([
            'subguestsFirstname' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
            'subguestsLastname' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
            'subguestsDob' => 'required|date',
            'subguestsGender' => 'required',
            'subguestsContactnumber' => ['required', 'regex:/^(09|\+639)\d{9}$/'],
        ], [
            'subguestsFirstname.required' => 'The first name is required.',
            'subguestsLastname.required' => 'The last name is required.',
            'subguestsDob.required' => 'The date of birth is required.',
            'subguestsGender.required' => 'The gender is required.',
            'subguestsContactnumber.required' => 'The contact number is required.',
            'subguestsContactnumber.regex' => 'The contact number must be a valid Philippine number, starting with 09 or +639 and followed by 9 digits.',
        ]);

        $this->subguests[] = [
            'firstname' => $this->subguestsFirstname,
            'middlename' => $this->subguestsMiddlename,
            'lastname' => $this->subguestsLastname,
            'dob' => $this->subguestsDob,
            'gender' => $this->subguestsGender,
            'contactnumber' => $this->subguestsContactnumber,
        ];

        session()->flash('subguest-message', 'Subguest added successfully.');
        $this->reset(['subguestsFirstname', 'subguestsMiddlename', 'subguestsLastname', 'subguestsDob', 'subguestsGender', 'subguestsContactnumber']);
    }


    public function removeSubGuest($index)
    {
        unset($this->subguests[$index]);
        $this->subguests = array_values($this->subguests);
        session()->flash('subguest-message', 'Subguest removed successfully.');
    }
    public function getAvailableRooms()
    {
        $checkIn = Carbon::parse($this->checkIn);
        $checkOut = Carbon::parse($this->checkOut);

        $totalChildren = is_null($this->totalChildren) ? 0 : (int)$this->totalChildren;
        $totalGuests = is_null($this->totalGuests) ? 0 : (int)$this->totalGuests;
        $total = $totalChildren + $totalGuests;

        // Get booked rooms during the selected period
        $bookedRooms = Reservation::whereIn('Status', ['Checked In', 'Reserved', 'Booked'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('DateCheckIn', [$checkIn, $checkOut])
                    ->orWhereBetween('DateCheckOut', [$checkIn, $checkOut])
                    ->orWhere(function ($query) use ($checkIn, $checkOut) {
                        $query->where('DateCheckIn', '<=', $checkIn)
                            ->where('DateCheckOut', '>=', $checkOut);
                    });
            })
            ->pluck('room_number_id'); // Only get room_number_id

        // Get available rooms that are not booked
        $availableRooms = RoomNumber::whereHas('room', function ($query) use ($total) {
            $query->where('Capacity', '>=', $total);
        })->get();

        $promotion = Promotion::where('StartDate', '<=', $checkIn)
            ->where('EndDate', '>=', $checkOut)
            ->first();

        // Apply discount if promotion is active
        if ($promotion && $promotion->discountedRooms) {
            foreach ($availableRooms as $roomNumber) {
                foreach ($promotion->discountedRooms as $discountedRoom) {
                    if ($discountedRoom->RoomId == $roomNumber->RoomId) {
                        $roomNumber->discount = $promotion->Discount;
                        break; // No need to check further once discount is found
                    }
                }
            }
        }

        // Mark rooms as booked or available
        foreach ($availableRooms as $roomNumber) {
            $roomNumber->isBooked = in_array($roomNumber->room_number_id, $bookedRooms->toArray());
        }



        return collect($availableRooms);
    }



    public function saveBooking()
    {

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


        if ($this->selectedGuestId) {
            $guest = Guest::find($this->selectedGuestId);
        } else {


            $this->validate([
                'firstname' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
                'lastname' => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
                'dob' => 'required|date',
                'gender' => 'required',
                'email' => 'required|email|unique:guests,EmailAddress',
                'street' => 'required',
                'selectedBrgy' => 'required',
                'selectedCity' => 'required',
                'selectedProvince' => 'required',
                'contactnumber' => ['required', 'regex:/^(09|\+639)[0-9]{9}$/', 'unique:guests,ContactNumber'],
            ]);
            if (
                $this->selectedGuestId || $this->firstname || $this->middlename || $this->lastname || $this->dob ||
                $this->gender || $this->email || $this->street || $this->brgy || $this->city ||
                $this->province || $this->contactnumber
            ) {
                $guest = new Guest();

                $guest->FirstName = $this->firstname;
                $guest->MiddleName = $this->middlename;
                $guest->LastName = $this->lastname;
                $guest->Birthdate = $this->dob;
                $guest->Gender = $this->gender;
                $guest->EmailAddress = $this->email;
                $guest->Street = $this->street;
                $guest->Brgy = $brgy;
                $guest->City = $city;
                $guest->Province = $province;
                $guest->ContactNumber = $this->contactnumber;
                $guest->Password = bcrypt('password');
                $guest->DateCreated = date('Y-m-d');
                $guest->TimeCreated = date('H:i:s');

                $guest->save();

                Mail::to($this->email)->send(new GuestBooking($guest, 'password'));
            } else {
                session()->flash('message', 'Please select or enter the guest information.');
                return;
            }
        }

        if (!$this->selectedRoomId) {
            session()->flash('message', 'Please select the room first');
            return;
        }

        if (!$this->paymentType) {
            session()->flash('message', 'Please select the payment method');

            return;
        }


        $room = Room::find($this->selectedRoomId);

        $totalAmenities = 0;

        foreach ($this->selectedAmenities as $amenity) {
            $totalAmenities += $amenity['price'] * $amenity['quantity'];
        }

        $reservation = new Reservation();
        $reservation->room_number_id = $this->selectedRoomNumberId;
        $reservation->GuestId = $guest->GuestId;
        $reservation->DateCheckIn = $this->checkIn;
        $reservation->DateCheckOut = $this->checkOut;
        $reservation->TotalCost =  $this->discountedRoomRate;
        $reservation->Status = 'Booked';
        $reservation->Discount = ($room->RoomPrice * $this->lengthOfStay) - $this->discountedRoomRate;

        $reservation->OriginalCost =  $room->RoomPrice * $this->lengthOfStay;
        $reservation->TotalAdult = $this->totalGuests;
        $reservation->TotalChildren = $this->totalChildren ?? 0;
        $reservation->Source = 'Walk In';

        if ($this->discount) {
            $reservation->DiscountType = "Event Promotion";
        } else {
            $reservation->DiscountType =   $this->discountType;
        }
        $minPayment = 0.3 * ($this->discountedRoomRate + $totalAmenities);
        $totalPayment = $this->discountedRoomRate + $totalAmenities;

        if ($this->paymentAmount == 0) {
            session()->flash('message', 'Please enter the payment amount');
            return;
        }

        if ($this->paymentAmount < $minPayment || $this->paymentAmount > $totalPayment) {
            session()->flash('message', 'Payment amount must be at least 30% (' . number_format($minPayment, 2) . ') or the full payment (' . number_format($totalPayment, 2) . ').');
            return;
        }


        if ($this->paymentAmount == $totalPayment) {
            $reservation->Status = 'Booked';
        } else {
            $reservation->Status = 'Reserved';
        }





        $purpose = "";

        if ($this->discountType == 'Senior Citizen' || $this->discountType == 'PWD') {
            $purpose = ($this->discountType == 'Senior Citizen') ? "Senior Citizen Discount" : "PWD Discount";

            if (empty($this->idNumber)) {
                session()->flash('message', 'Please enter the ID Number');
                return;
            }


            if ($this->discountType == 'Senior Citizen') {
                $idPattern = '/^[A-Z]{3}-OSCA-\d{4}-\d{5}$/';
                $purpose = "Senior Citizen Discount";

                if (!preg_match($idPattern, $this->idNumber)) {
                    session()->flash('message', 'Invalid Senior Citizen ID format. Use "XXX-OSCA-XXXX-XXXXX"');
                    return;
                }
            }

            if ($this->discountType == 'PWD') {
                $idPattern = '/^[A-Z]{2,3}-\d{2}-\d{4}-\d{6}$/';
                $purpose = "PWD Discount";

                if (!preg_match($idPattern, $this->idNumber)) {
                    session()->flash('message', 'Invalid PWD ID format. Use "XXX-XX-XXXX-XXXXXX"');
                    return;
                }
            }

            $reservation->IdNumber = $this->idNumber;
        } else if ($this->discountType == 'None') {
            $purpose = "No Discount";
        }

        $reservation->DateCreated = date('Y-m-d');
        $reservation->TimeCreated = date('H:i:s');


        $reservation->save();

        foreach ($this->subguests as $subguest) {
            $reservation->subguests()->create([
                'FirstName' => $subguest['firstname'],
                'MiddleName' => $subguest['middlename'],
                'LastName' => $subguest['lastname'],
                'Birthdate' => $subguest['dob'],
                'Gender' => $subguest['gender'],
                'ContactNumber' => $subguest['contactnumber'],
            ]);
        }


        $reservation->reservationAmenities()->createMany(
            collect($this->selectedAmenities)->map(function ($amenity) {
                return [
                    'AmenitiesId' => $amenity['id'],
                    'Quantity' => $amenity['quantity'],
                    'TotalCost' => $amenity['price'] * $amenity['quantity'],
                ];
            })
        );




        if ($this->paymentAmount != 0) {

            if ($this->paymentType == 'Gcash') {

                $reservation->payments()->create([
                    'GuestId' => $guest->GuestId,
                    'AmountPaid' => $this->paymentAmount ?? 0,
                    'DateCreated' => date('Y-m-d'),
                    'TimeCreated' => date('H:i:s'),
                    'Status' => 'Pending',
                    'PaymentType' => $this->paymentType,
                    'ReferenceNumber' => $this->generateReferenceNumber(),
                    'Purpose' => $purpose,
                ]);
            } else {
                $reservation->payments()->create([
                    'GuestId' => $guest->GuestId,
                    'AmountPaid' => $this->paymentAmount ?? 0,
                    'DateCreated' => date('Y-m-d'),
                    'TimeCreated' => date('H:i:s'),
                    'Status' => 'Paid',
                    'PaymentType' => $this->paymentType,
                    'ReferenceNumber' => $this->generateReferenceNumber(),
                    'Purpose' => $purpose,
                ]);
            }
        }


        $this->reset();

        session()->flash('message', 'Room Booking created successfully.');
    }

    public function filterRoom()
    {
        $this->validate([
            'checkIn' => 'required|date',
            'checkOut' => 'required|date|after:checkIn',
            'totalGuests' => 'required|integer|min:1',
        ]);
    }

    public function selectGuest($guestId)
    {
        $guest = Guest::find($guestId);

        $this->firstname = $guest->FirstName;
        $this->middlename = $guest->MiddleName;
        $this->lastname = $guest->LastName;
        $this->dob = $guest->Birthdate;
        $this->street = $guest->Street;
        $this->city = $guest->City;
        $this->province = $guest->Province;
        $this->contactnumber = $guest->ContactNumber;
        $this->email = $guest->EmailAddress;
        $this->gender = $guest->Gender;
        $this->dispatch('close-modal');
        $this->selectedGuestId = $guestId;


        $this->fetchRegions();

        foreach ($this->apiProvince as $prov) {
            if ($prov['name'] == $guest->Province) {
                $this->selectedProvince = $prov['code'];
                break;
            }
        }

        $this->fetchCities();

        foreach ($this->apiCity as $cit) {
            if ($cit['name'] == $guest->City) {
                $this->selectedCity = $cit['code'];
                break;
            }
        }

        $this->fetchBarangays();

        foreach ($this->apiBrgy as $b) {
            if ($b['name'] == $guest->Brgy) {
                $this->selectedBrgy = $b['code'];
                break;
            }
        }
    }
    public function updateAmenityQuantityAll()
    {
        $maxQuantityExceeded = false; // Track if any quantity exceeds the limit

        foreach ($this->quantity as $amenityId => $newQuantity) {
            $amenity = Amenities::find($amenityId);

            if ($amenity) {
                // Cap the quantity at 10 and set a flag for a flash message if exceeded
                if ($newQuantity > 10) {
                    $newQuantity = 10;
                    $maxQuantityExceeded = true;
                }

                // Search for the amenity in the selected amenities
                $index = collect($this->selectedAmenities)->search(fn($item) => $item['id'] === $amenityId);

                if ($newQuantity > 0) {
                    // If the amenity already exists in selectedAmenities, update it; otherwise, add it
                    if ($index !== false) {
                        $this->selectedAmenities[$index]['quantity'] = $newQuantity;
                    } else {
                        $this->selectedAmenities[] = [
                            'id' => $amenityId,
                            'name' => $amenity->Name,
                            'price' => $amenity->Price,
                            'quantity' => $newQuantity,
                        ];
                    }
                } else {
                    // Remove the amenity if quantity is zero
                    if ($index !== false) {
                        unset($this->selectedAmenities[$index]);
                        $this->selectedAmenities = array_values($this->selectedAmenities); // Reindex the array
                    }
                }
            }
        }

        // Recalculate the total cost after processing all amenities
        $this->total = $this->computeTotal();

        // Show messages
        if ($maxQuantityExceeded) {
            session()->flash('error', 'The maximum quantity for each amenity is 10.');
        }
        session()->flash('message', 'Amenities updated successfully.');

        $this->dispatch('close-modal');
    }

    public function applyDiscount()
    {
        $checkIn = Carbon::parse($this->checkIn);
        $checkOut = Carbon::parse($this->checkOut);
        $this->lengthOfStay = $checkIn->diffInDays($checkOut);

        $room = Room::find($this->selectedRoomId);
        $total = 0;
        if ($room) {
            $total = $room->RoomPrice * $this->lengthOfStay;
        }



        $promotions = Promotion::where('StartDate', '<=', $checkIn)
            ->where('EndDate', '>=', $checkOut)
            ->first();

        if ($promotions) {

            foreach ($promotions->discountedRooms as $discount) {
                if ($discount->RoomId === $this->selectedRoomId) {
                    $this->discount = $promotions;
                    break;
                }
            }


            if ($this->discount) {

                $this->discountedRoomRate = $total -  ($total * ($this->discount->Discount / 100));
                $this->discountType = "Promotion";
            } else {

                if ($this->discountType != 'None') {
                    $this->discountedRoomRate = $total -  ($this->total * (10 / 100));
                } else {
                    $this->discountedRoomRate = $total;
                }
            }
        } else {
            if ($this->discountType != 'None') {
                $this->discountedRoomRate = $total -  ($total * (10 / 100));
            } else {
                $this->discountedRoomRate = $total;
            }
        }
    }


    public function selectRoom($roomId)
    {
        $rNumber = RoomNumber::find($roomId);

        $this->selectedRoomNumberId = $rNumber->room_number_id;
        $room = Room::find($rNumber->RoomId);
        $this->selectedRoom = $room;
        $this->selectedRoomId = $room->RoomId;

        $this->dispatch('close-modal');

        $checkIn = Carbon::parse($this->checkIn);
        $checkOut = Carbon::parse($this->checkOut);
        $this->lengthOfStay = $checkIn->diffInDays($checkOut);

        $this->total = $this->computeTotal();


        $promotions = Promotion::where('StartDate', '<=', $checkIn)
            ->where('EndDate', '>=', $checkOut)
            ->first();

        if ($promotions) {

            foreach ($promotions->discountedRooms as $discount) {
                if ($discount->RoomId === $roomId) {
                    $this->discount = $promotions;
                    break;
                }
            }


            if ($this->discount) {

                $this->discountedRoomRate = $this->total -  ($this->total * ($this->discount->Discount / 100));
            } else {
                $this->discountedRoomRate = $this->total;
            }
        } else {
            $this->discountedRoomRate = $this->total;
        }
    }


    public function computeTotal()
    {

        $room = Room::find($this->selectedRoomId);

        $this->total = 0;
        $this->totalAmenitiesCost = 0;
        if ($room) {
            $this->total = $room->RoomPrice * $this->lengthOfStay;
        }

        foreach ($this->selectedAmenities as $amenity) {
            $this->totalAmenitiesCost += $amenity['price'] * $amenity['quantity'];
        }
        return $this->total + $this->totalAmenitiesCost;
    }

    public function generateReferenceNumber()
    {
        return 'REF-' . date('YmdHis');
    }
}
