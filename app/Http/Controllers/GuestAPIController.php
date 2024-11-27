<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Amenities;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Http;
use App\Models\Room;
use Carbon\Carbon;
use App\Models\Promotion;
use App\Models\Notification;
use Illuminate\Support\Facades\Request as FacadesRequest;
use App\Models\SystemLog;
use App\Models\SubGuest;
use App\Models\Payment;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class GuestAPIController extends Controller
{
    private $apiKey;


    private function isPasswordValid($password)
    {

        if (strlen($password) < 8) {
            return false;
        }

        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }

        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }

        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        if (!preg_match('/[\W_]/', $password)) {
            return false;
        }

        return true;
    }
    public function create(Request $request)
    {

        $firstname = trim($request->input('firstname'));
        if (empty($firstname)) {
            return response()->json(['error' => 'First name is required.'], 200);
        } elseif (!is_string($firstname) || strlen($firstname) > 255) {
            return response()->json(['error' => 'First name must be a string and cannot exceed 255 characters.'], 200);
        } elseif (!preg_match('/^[a-zA-Z\s]+$/', $firstname)) {
            return response()->json(['error' => 'First name must only contain letters and spaces.'], 200);
        }


        $lastname = trim($request->input('lastname'));
        if (empty($lastname)) {
            return response()->json(['error' => 'Last name is required.'], 200);
        } elseif (!is_string($lastname) || strlen($lastname) > 255) {
            return response()->json(['error' => 'Last name must be a string and cannot exceed 255 characters.'], 200);
        } elseif (!preg_match('/^[a-zA-Z\s]+$/', $lastname)) {
            return response()->json(['error' => 'Last name must only contain letters and spaces.'], 200);
        }

        $middlename = trim($request->input('middlename'));
        if ($middlename != '') {
            if (!is_string($middlename)) {
                return response()->json(['error' => 'Middle name must be a string.'], 200);
            } elseif (strlen($middlename) > 255) {
                return response()->json(['error' => 'Middle name cannot exceed 255 characters.'], 200);
            } elseif (!preg_match('/^[a-zA-Z\s]+$/', $middlename)) {
                return response()->json(['error' => 'Middle name must only contain letters and spaces.'], 200);
            }
        }

        if (empty($request->input('street'))) {
            return response()->json(['error' => 'Street is required.'], 200);
        } elseif (!is_string($request->input('street')) || strlen($request->input('street')) > 255) {
            return response()->json(['error' => 'Street must be a string and cannot exceed 255 characters.'], 200);
        }

        if (empty($request->input('city'))) {
            return response()->json(['error' => 'City is required.'], 200);
        } elseif (!is_string($request->input('city')) || strlen($request->input('city')) > 255) {
            return response()->json(['error' => 'City must be a string and cannot exceed 255 characters.'], 200);
        }

        if (empty($request->input('province'))) {
            return response()->json(['error' => 'Province is required.'], 200);
        } elseif (!is_string($request->input('province')) || strlen($request->input('province')) > 255) {
            return response()->json(['error' => 'Province must be a string and cannot exceed 255 characters.'], 200);
        }

        if (empty($request->input('birthdate'))) {
            return response()->json(['error' => 'Birthdate is required.'], 200);
        } elseif (!strtotime($request->input('birthdate'))) {
            return response()->json(['error' => 'Birthdate must be a valid date.'], 200);
        }

        if (empty($request->input('gender'))) {
            return response()->json(['error' => 'Gender is required.'], 200);
        } elseif (!is_string($request->input('gender')) || strlen($request->input('gender')) > 255) {
            return response()->json(['error' => 'Gender must be a string and cannot exceed 255 characters.'], 200);
        }

        if (empty($request->input('contactnumber'))) {
            return response()->json(['error' => 'Contact number is required.'], 200);
        } elseif (!is_string($request->input('contactnumber')) || strlen($request->input('contactnumber')) > 12) {
            return response()->json(['error' => 'Contact number must be a string and cannot exceed 12 characters.'], 200);
        }

        if (empty($request->input('emailaddress'))) {
            return response()->json(['error' => 'Email address is required.'], 200);
        } elseif (!filter_var($request->input('emailaddress'), FILTER_VALIDATE_EMAIL) || strlen($request->input('emailaddress')) > 255) {
            return response()->json(['error' => 'Email address must be a valid email.'], 200);
        }

        if (empty($request->input('password'))) {
            return response()->json(['error' => 'Password is required.'], 200);
        } elseif (!is_string($request->input('password')) || strlen($request->input('password')) > 32) {
            return response()->json(['error' => 'Password must be a string and cannot exceed 32 characters.'], 200);
        }

        $validatedData = $request->only([
            'firstname',
            'lastname',
            'middlename',
            'street',
            'city',
            'province',
            'birthdate',
            'gender',
            'contactnumber',
            'emailaddress',
            'password'
        ]);

        $existingGuest = Guest::where('EmailAddress', $validatedData['emailaddress'])->first();

        if ($existingGuest) {
            return response()->json(['error' => 'Email address already exists'], 200);
        }

        if (Guest::where('ContactNumber', $validatedData['contactnumber'])->first()) {
            return response()->json(['error' => 'Contact number already exists'], 200);
        }


        $contactNumber = $request->input('contactnumber');
        if (!preg_match('/^(09\d{9}|\+639\d{9})$/', $contactNumber)) {
            return response()->json(['error' => 'Invalid Philippine contact number format'], 200);
        }

        $password = $validatedData['password'];



        if (!$this->isPasswordValid($password)) {
            return response()->json(['error' => 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.']);
        }

        $birthdate = Carbon::parse($validatedData['birthdate']);
        $now = Carbon::now();

        if ($birthdate->diffInYears($now) < 18) {
            return response()->json(['error' => 'Guest must be at least 18 years old']);
        }

        $validatedData['firstname'] = ucwords(strtolower($validatedData['firstname']));
        $validatedData['lastname'] = ucwords(strtolower($validatedData['lastname']));
        $validatedData['middlename'] = ucwords(strtolower($validatedData['middlename']));
        $guest = Guest::create([
            'FirstName' => $validatedData['firstname'],
            'LastName' => $validatedData['lastname'],
            'MiddleName' => $validatedData['middlename'] ?? null,
            'Street' => $validatedData['street'],
            'City' => $validatedData['city'],
            'Province' => $validatedData['province'],
            'Birthdate' => $validatedData['birthdate'],
            'Gender' => $validatedData['gender'],
            'ContactNumber' => $validatedData['contactnumber'],
            'Brgy' => $request->brgy ?? "",
            'EmailAddress' => $validatedData['emailaddress'],
            'password' => bcrypt($validatedData['password']),
            'DateCreated' => now()->toDateString(),
            'TimeCreated' => now()->toTimeString(),
        ]);

        SystemLog::create([
            'log' => 'New guest created from IP: ' . FacadesRequest::ip() .
                ' for email: ' . $validatedData['emailaddress'] .
                ' on ' . now()->toDateTimeString(),
            'action' => 'Create Guest',
            'date_created' => now()->toDateString(),
        ]);



        return response()->json(
            [
                'message' => 'Guest created successfully',
                'guest' => $guest,
                'token' => $guest->createToken('Samahang-Nayon')->plainTextToken
            ],
            201
        );
    }


    public function getCurrentUser(Request $request)
    {
        $guest = Auth::guard('api')->user();
        if ($guest) {
            return response()->json($guest);
        }

        return response()->json(['error' => 'Unauthorized'], 200);
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'emailaddress' => 'required|string|max:255',
            'password' => 'required|string',
        ]);

        $guest = Guest::where('EmailAddress', $validatedData['emailaddress'])
            ->orWhere('ContactNumber',  $validatedData['emailaddress'])->first();

        if (!$guest) {
            return response()->json(['error' => 'Guest not found'], 200);
        }

        if (!Hash::check($validatedData['password'], $guest->Password)) {
            SystemLog::create([
                'log' => 'Guest login failed from IP: ' . FacadesRequest::ip() .
                    ' for email: ' . $validatedData['emailaddress'] .
                    ' on ' . now()->toDateTimeString(),
                'action' => 'Guest Login',
                'date_created' => now()->toDateString(),
            ]);
            return response()->json(['error' => 'Invalid password'], 200);
        }



        $token = $guest->createToken('Samahang-Nayon')->plainTextToken;

        return response()->json(['token' => $token, 'message' => 'Successfully Login'], 200);
    }


    public function getAllUser()
    {
        $guest = Auth::guard('api')->user();
        if ($guest) {
            return response()->json($guest);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function createReservation(Request $request)
    {

        $validatedData = $request->validate([
            'room_id' => 'required|integer',
            'room_number_id' => 'required|integer',
            'check_in' => 'required|date',
            'check_out' => 'required|date',
            'amenities' => 'nullable|array',
            'amenities.*.id' => 'required_with:amenities|integer',
            'amenities.*.name' => 'required_with:amenities|string',
            'amenities.*.price' => 'required_with:amenities|numeric',
            'amenities.*.quantity' => 'required_with:amenities|integer',
            'sub_guests' => 'nullable|array',
            'payment_option' => 'required|string|in:full,partial,pay_later',
            'total_adult' => 'required|integer',
            'total_children' => 'required|integer',
            'discountType' => 'nullable|string',
            'id_number' => 'nullable|string'
        ]);



        $guest = Auth::guard('api')->user();
        if (!$guest) {
            return response()->json(['error' => 'Invalid Session'], 200);
        }

        if (Reservation::where('room_number_id', $validatedData['room_number_id'])
            ->where('DateCheckIn', '<=', $validatedData['check_out'])
            ->where('DateCheckOut', '>=', $validatedData['check_in'])
            ->where('Status', '!=', 'Cancelled')
            ->exists()
        ) {
            return response()->json(['error' => 'Room is not available'], 200);
        }

        $room = Room::find($validatedData['room_id']);

        $checkIn = Carbon::parse($validatedData['check_in']);
        $checkOut = Carbon::parse($validatedData['check_out']);
        $lengthOfStay = $checkIn->diffInDays($checkOut);

        $totalCost = $room->RoomPrice * $lengthOfStay;


        if ($validatedData['discountType'] == 'Senior Citizen' || $validatedData['discountType'] == 'PWD') {
            $totalCost = $totalCost - ($totalCost * 0.10);
        } else {
            $promotion = Promotion::where('StartDate', '<=', $checkOut)
                ->where('EndDate', '>=', $checkIn)
                ->whereHas('discountedRooms', function ($query) use ($room) {
                    $query->where('RoomId', $room->RoomId);
                })
                ->first();

            if ($promotion) {
                $totalCost = $totalCost - ($totalCost * ($promotion->Discount / 100));
            }
        }




        $reservation = Reservation::create([
            'GuestId' => $guest->GuestId,
            'room_number_id' => $validatedData['room_number_id'],
            'DateCheckIn' => $validatedData['check_in'],
            'DateCheckOut' => $validatedData['check_out'],
            'Status' => $validatedData['payment_option'] == 'partial'
                ? 'Reserved'
                : ($validatedData['payment_option'] == 'pay_later'
                    ? 'Unconfirmed Reservation'
                    : 'Booked'),

            'TotalCost' => $totalCost,
            'DateCreated' => now()->toDateString(),
            'TimeCreated' => now()->toTimeString(),
            'TotalAdult' => $validatedData['total_adult'],
            'TotalChildren' => $validatedData['total_children'],
            'OriginalCost' => $room->RoomPrice * $lengthOfStay,
            'Discount' => $validatedData['discountType'] != '' ? 10 : ($promotion->Discount ?? 0),
            'Source' => 'Online',
            'DiscountType' => $validatedData['discountType'] ?? null,
            'IdNumber' => $validatedData['id_number'] ?? null
        ]);

        if ($validatedData['payment_option'] == 'pay_later') {
            $message = "Thank you for choosing Samahang Nayon Hotel, {$guest->FirstName}!\n\n" .
                "Your reservation has been created successfully with the following details:\n" .
                "Room ID: {$validatedData['room_id']}\n" .
                "Check-in Date: {$validatedData['check_in']}\n" .
                "Check-out Date: {$validatedData['check_out']}\n" .
                "Total Adults: {$validatedData['total_adult']}\n" .
                "Total Children: {$validatedData['total_children']}\n" .
                "Total Cost: {$totalCost}\n" .
                "Original Cost: " . ($room->RoomPrice * $lengthOfStay) . "\n" .
                "Discount Applied: " . ($validatedData['discountType'] != '' ? 10 : ($promotion->Discount ?? 0)) . "\n\n" .
                "Your reservation is currently marked as 'Pay Later'. Please ensure payment is completed before check-in to confirm your booking.\n\n" .
                "We look forward to welcoming you to Samahang Nayon Hotel!";
        } else {
            $message = "Thank you for choosing Samahang Nayon Hotel, {$guest->FirstName}!\n\n" .
                "Your reservation has been created successfully with the following details:\n" .
                "Room ID: {$validatedData['room_id']}\n" .
                "Check-in Date: {$validatedData['check_in']}\n" .
                "Check-out Date: {$validatedData['check_out']}\n" .
                "Total Adults: {$validatedData['total_adult']}\n" .
                "Total Children: {$validatedData['total_children']}\n" .
                "Total Cost: {$totalCost}\n" .
                "Original Cost: " . ($room->RoomPrice * $lengthOfStay) . "\n" .
                "Discount Applied: " . ($validatedData['discountType'] != '' ? 10 : ($promotion->Discount ?? 0)) . "\n\n" .
                "We look forward to welcoming you to Samahang Nayon Hotel!";
        }




        $response = Http::post('https://nasa-ph.com/api/send-sms', [
            'phone_number' => $guest->ContactNumber,
            'message' => $message
        ]);



        $totalPayment = 0;

        $totalAmenityCost = 0;
        foreach ($validatedData['amenities'] as $amenity) {

            $sum = $amenity['price'] * $amenity['quantity'];


            $reservation->reservationAmenities()->create([
                'AmenitiesId' => $amenity['id'],
                'Quantity' => $amenity['quantity'],
                'TotalCost' => $sum,
            ]);
            $totalPayment += $sum;
            $totalAmenityCost += $sum;
        }

        foreach ($validatedData['sub_guests'] as $sub_guest) {
            $reservation->subGuests()->create([
                'FirstName' => $sub_guest['first_name'],
                'LastName' => $sub_guest['last_name'],
                'MiddleName' => $sub_guest['middle_name'], // Corrected key
                'ContactNumber' => $sub_guest['contact_number'], // Corrected key
                'Birthdate' => $sub_guest['birthdate'], // Corrected key
                'Gender' => $sub_guest['gender']
            ]);
        }


        if ($validatedData['discountType'] == 'Senior Citizen' || $validatedData['discountType'] == 'PWD') {
            $partialPaymentAmount = (($room->RoomPrice * $lengthOfStay) - (($room->RoomPrice * $lengthOfStay) * 0.1)) * 0.30;
        } else {
            $promotion = Promotion::where('StartDate', '<=', $checkOut)
                ->where('EndDate', '>=', $checkIn)
                ->whereHas('discountedRooms', function ($query) use ($room) {
                    $query->where('RoomId', $room->RoomId);
                })
                ->first();

            if ($promotion) {
                $partialPaymentAmount = (($room->RoomPrice * $lengthOfStay) - (($room->RoomPrice * $lengthOfStay) * ($promotion->Discount / 100))) * 0.30;
            } else {
                $partialPaymentAmount = (($room->RoomPrice * $lengthOfStay) * 0.30);
            }
        }

        if ($validatedData['payment_option'] == 'pay_later') {

            $notification = new Notification();
            $notification->isForGuest = false;
            $notification->Title = 'New Reservation';
            $notification->Type = 'Reservation';
            $notification->Message = 'A new reservation has been created for ' . $guest->FirstName . ' ' . $guest->LastName . '. Please confirm and proceed with the necessary actions.';
            $notification->save();

            SystemLog::create([
                'log' => 'Reservation created successfully from IP: ' . FacadesRequest::ip() .
                    ' for email: ' . $request->email .
                    ' on ' . date('Y-m-d H:i:s'),
                'action' => 'Create Reservation',
                'date_created' => date('Y-m-d')
            ]);

            $guest = Guest::find($guest->GuestId);

            $message = "Dear {$guest->FirstName},\n\n" .
                "Your reservation has been successfully created. Please note that payment must be completed within 24 hours to secure your booking.\n\n" .
                "Reservation Details:\n" .

                "Amount Due: {$partialPaymentAmount}\n" .

                "Date: " . now()->toDateString() . "\n" .
                "Status: Pending Payment\n\n" .
                "Thank you for choosing us. We look forward to serving you!";

            $response = Http::post('https://nasa-ph.com/api/send-sms', [
                'phone_number' => $guest->ContactNumber,
                'message' => $message
            ]);

            return response()->json($response->json());
        } elseif ($validatedData['payment_option'] == 'partial') {
            $reservation->payments()->create([
                'GuestId' => $guest->GuestId,
                'AmountPaid' => $partialPaymentAmount ?? 0,
                'DateCreated' => date('Y-m-d'),
                'TimeCreated' => date('H:i:s'),
                'Status' => 'Pending',
                'PaymentType' => 'Gcash',
                'ReferenceNumber' => $this->generateReferenceNumber(),
                'Purpose' => "Room Reservation",
            ]);


            $this->apiKey = 'c2tfdGVzdF80OE1nWVk3U0dLdDY5dkVQZnRnZGpmS286';
            $data = [
                'data' => [
                    'attributes' => [
                        'cancel_url' => url('/cancel/' . $reservation->payments->first()->ReferenceNumber),
                        'success_url' => url('/success/' . $reservation->payments->first()->ReferenceNumber),


                        'billing' => [
                            'name' => $guest->FirstName . ' ' . $guest->LastName,
                            'email' => $guest->EmailAddress,
                            'phone' => $guest->ContactNumber
                        ],
                        'send_email_receipt' => true,
                        'show_description' => true,
                        'show_line_items' => true,
                        'description' => 'Room Reservation Partial Payment',
                        'line_items' => [
                            [
                                'currency' => 'PHP',
                                'amount' => (int)($partialPaymentAmount * 100),

                                'description' => 'Room Reservation Partial Payment',
                                'name' => $reservation->roomNumber->room->RoomType,
                                'quantity' => 1
                            ],

                        ],
                        'payment_method_types' => ['gcash'],
                        'reference_number' => $reservation->payments->first()->ReferenceNumber,
                    ]
                ]
            ];

            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Basic ' . $this->apiKey,
                ])->post('https://api.paymongo.com/v1/checkout_sessions', $data);



                if ($response->successful()) {
                    return response()->json($response->json());
                } else {

                    return response()->json([
                        'error' => $response->body(),
                        'status' => $response->status()
                    ], $response->status());
                }
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {

            $reservation->payments()->create([
                'GuestId' => $guest->GuestId,
                'AmountPaid' => $totalCost + $totalPayment ?? 0,
                'DateCreated' => date('Y-m-d'),
                'TimeCreated' => date('H:i:s'),
                'Status' => 'Pending',
                'PaymentType' => 'Gcash',
                'ReferenceNumber' => $this->generateReferenceNumber(),
                'Purpose' => "Room Reservation",
            ]);

            $this->apiKey = 'c2tfdGVzdF80OE1nWVk3U0dLdDY5dkVQZnRnZGpmS286';
            $data = [
                'data' => [
                    'attributes' => [

                        'cancel_url' => url('/cancel/' . $reservation->payments->first()->ReferenceNumber),
                        'success_url' => url('/success/' . $reservation->payments->first()->ReferenceNumber),

                        'billing' => [
                            'name' => $guest->FirstName . ' ' . $guest->LastName,
                            'email' => $guest->EmailAddress,
                            'phone' => $guest->ContactNumber
                        ],
                        'send_email_receipt' => true,
                        'show_description' => true,
                        'show_line_items' => true,
                        'description' => 'Room Reservation',
                        'line_items' => [
                            [
                                'currency' => 'PHP',
                                'amount' => (int)($reservation->roomNumber->room->RoomPrice * 100) -
                                    (($reservation->roomNumber->room->RoomPrice * 100) * (($promotion->Discount ?? 0) / 100)),

                                'description' => 'Room Reservation',
                                'name' => $reservation->roomNumber->room->RoomType,
                                'quantity' => $lengthOfStay
                            ],
                            ...$reservation->reservationAmenities->map(function ($amenity) {
                                return [
                                    'currency' => 'PHP',
                                    'amount' => (int)($amenity->amenity->Price * 100),
                                    'description' => 'Amenity',
                                    'name' => $amenity->amenity->Name,
                                    'quantity' => $amenity->Quantity
                                ];
                            })
                        ],
                        'payment_method_types' => ['gcash'],
                        'reference_number' => $reservation->payments->first()->ReferenceNumber,
                    ]
                ]
            ];

            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Basic ' . $this->apiKey,
                ])->post('https://api.paymongo.com/v1/checkout_sessions', $data);



                if ($response->successful()) {

                    $notification = new Notification();
                    $notification->isForGuest = false;
                    $notification->Title = 'New Reservation';
                    $notification->Type = 'Reservation';
                    $notification->Message = 'A new reservation has been created for ' . $guest->FirstName . ' ' . $guest->LastName . '. Please confirm and proceed with the necessary actions.';
                    $notification->save();


                    SystemLog::create([
                        'log' => 'Reservation created successfully from IP: ' . FacadesRequest::ip() .
                            ' for email: ' . $request->email .
                            ' on ' . date('Y-m-d H:i:s'),
                        'action' => 'Create Reservation',
                        'date_created' => date('Y-m-d')
                    ]);



                    return response()->json($response->json());
                } else {

                    return response()->json([
                        'error' => $response->body(),
                        'status' => $response->status()
                    ], $response->status());
                }
            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    }
    public function getReservation(Request $request)
    {
        $guest = Auth::guard('api')->user();
        if (!$guest) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $status = $request->status;

        $reservations = Reservation::where('GuestId', $guest->GuestId)
            ->when($status, function ($query, $status) {
                if (in_array($status, ['Booked', 'Reserved'])) {
                    return $query->whereIn('Status', ['Booked', 'Reserved', 'Unconfirmed Reservation']);
                }
                return $query->where('Status', $status);
            })
            ->with(['roomNumber', 'roomNumber.room', 'reservationAmenities', 'payments'])
            ->orderBy('DateCheckIn', 'desc')
            ->get();


        $reservations = $reservations->map(function ($reservation) {
            return array_map(function ($value) {
                if (is_string($value)) {
                    return mb_convert_encoding($value, 'UTF-8', 'auto');
                }
                return $value;
            }, $reservation->toArray());
        });

        return response()->json($reservations);
    }




    public function cancelReservation(Request $request)
    {
        $guest = Auth::guard('api')->user();
        if (!$guest) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $reservation = Reservation::find($request->reservation_id);

        if (!$reservation) {
            return response()->json(['error' => 'Reservation not found'], 404);
        }

        if ($reservation->Status == 'Cancelled') {
            return response()->json(['error' => 'Reservation already cancelled'], 200);
        }



        $checkIn = Carbon::parse($reservation->DateCheckIn);

        if ($checkIn->diffInDays(now()) >= 3) {
            return response()->json(['error' => 'Cannot cancel reservation with 3 days or less before check-in'], 200);
        }

        if (!$reservation) {
            return response()->json(['error' => 'Reservation not found'], 404);
        }

        $reservation->update([
            'Status' => 'Cancelled',
            'DateCancelled' => now()
        ]);

        $notification = new Notification();
        $notification->isForGuest = false;
        $notification->Title = 'Reservation Cancellation';
        $notification->Type = 'Cancellation';
        $notification->Message = 'The reservation for ' . $guest->FirstName . ' ' . $guest->LastName . ' has been canceled. The system has been updated automatically.';
        $notification->save();

        SystemLog::create([
            'log' => 'Reservation canceled successfully from IP: ' . FacadesRequest::ip() .
                ' for email: ' . $request->email .
                ' on ' . date('Y-m-d H:i:s'),
            'action' => 'Cancel Reservation',
            'date_created' => date('Y-m-d')
        ]);

        $message = "Your reservation has been cancelled successfully. We hope to see you again soon!";


        $response = Http::post('https://nasa-ph.com/api/send-sms', [
            'phone_number' => $guest->ContactNumber,
            'message' => $message
        ]);

        return response()->json(['message' => 'Reservation cancelled successfully'], 200);
    }

    public function getAmenities()
    {
        $amenities = Amenities::all();
        return response()->json($amenities);
    }


    public function generateReferenceNumber()
    {
        return 'REF-' . date('YmdHis');
    }

    public function getReservationDetails(Request $request)
    {
        $guest = Auth::guard('api')->user();

        // Check if the user is authenticated
        if (!$guest) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Validate the request data
        $request->validate([
            'reservation_id' => 'required|integer|exists:reservations,ReservationId', // Update to check for ReservationId
        ]);

        $reservation = Reservation::with(['payments', 'roomNumber', 'reservationAmenities.amenity', 'subGuests']) // Correct 'payment' to 'payments'
            ->where('ReservationId', $request->reservation_id) // Use where() instead of find()
            ->first();

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        return response()->json($reservation);
    }

    public function requestOtp(Request $request)
    {


        $validatedData = $request->validate([
            'contactnumber' => 'required|string|max:12',
        ]);

        $guest = Guest::where('ContactNumber', $validatedData['contactnumber'])->first();

        if (!$guest) {
            return response()->json(['message' => 'Guest not found'], 404);
        }

        $otp = rand(1000, 9999);


        $response = Http::post('https://nasa-ph.com/api/send-sms', [
            'phone_number' => $guest->ContactNumber,
            'message' => "Your OTP code is: $otp. Please use this code to reset your password.",
        ]);
        $token = $guest->createToken('Samahang-Nayon')->plainTextToken;

        return response()->json(['otp' => $otp, 'token' => $token, 'guest' => $guest], 200);
    }

    public function changePassword(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'password' => 'required|string',
        ]);

        // Get the authenticated guest
        $guest = Auth::guard('api')->user();
        if (!$guest) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $nGuest = Guest::find($guest->GuestId);

        $nGuest->Password = bcrypt($request->password);
        $nGuest->save();

        // Log the password change
        SystemLog::create([
            'log' => 'Password changed successfully from IP: ' . FacadesRequest::ip() .
                ' for email: ' . $guest->EmailAddress .
                ' on ' . date('Y-m-d H:i:s'),
            'action' => 'Change Password',
            'date_created' => date('Y-m-d')
        ]);

        return response()->json(['message' => $nGuest], 200);
    }

    public function addAmenities(Request $request)
    {



        $validatedData = $request->validate([
            'reservation_id' => 'required|integer',
            'amenities_id' => 'required|integer',
            'quantity' => 'required|integer',
            'total_cost' => 'required|numeric'
        ]);

        $reservation = Reservation::find($validatedData['reservation_id']);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }


        $reservation->reservationAmenities()->create([
            'AmenitiesId' => $validatedData['amenities_id'],
            'Quantity' => $validatedData['quantity'],
            'TotalCost' => $validatedData['total_cost'],
        ]);
    }

    public function addSubGuest(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'gender' => 'required|string',
            'reservation_id' => 'required|integer'

        ]);

        try {

            $subGuest = SubGuest::create([
                'FirstName' => $validatedData['first_name'],
                'LastName' => $validatedData['last_name'],
                'MiddleName' => $validatedData['middle_name'],
                'Birthdate' => $validatedData['birthdate'],
                'ContactNumber' => $validatedData['contact_number'],
                'EmailAddress' => $validatedData['email'],
                'Gender' => $validatedData['gender'],
                'ReservationId' => $validatedData['reservation_id']
            ]);


            return response()->json([
                'message' => 'Sub-guest added successfully',
                'sub_guest' => $subGuest
            ], 201);
        } catch (\Exception $e) {

            return response()->json(['message' => 'Failed to add sub-guest', 'error' => $e->getMessage()], 500);
        }
    }


    public function updateUser(Request $request)
    {

        $guest = Auth::guard('api')->user();
        if (!$guest) {
            return response()->json(['message' => 'Unauthorized'], 200);
        }

        $validatedData = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'password' => 'required|string|max:255'
        ]);

        if (!Hash::check($validatedData['password'], $guest->Password)) {
            return response()->json(['message' => 'Invalid password'], 200);
        }

        $nGuest = Guest::find($guest->GuestId);
        $nGuest->FirstName = $validatedData['firstName'];
        $nGuest->LastName = $validatedData['lastName'];
        $nGuest->MiddleName = $validatedData['middleName'];
        $nGuest->MiddleName = $validatedData['middleName'];
        $nGuest->save();

        return response()->json(['message' => 'Guest updated successfully', 'guest' => $guest], 200);
    }

    public function updatePhone(Request $request)
    {


        $guest = Auth::guard('api')->user();
        if (!$guest) {
            return response()->json(['message' => 'Unauthorized'], 200);
        }

        $validatedData = $request->validate([
            'contactNumber' => 'required|string|max:12',
            'password' => 'required|string|max:255'
        ]);


        if (!Hash::check($validatedData['password'], $guest->Password)) {
            return response()->json(['message' => 'Invalid password'], 200);
        }

        $nGuest = Guest::find($guest->GuestId);

        $nGuest->ContactNumber = $validatedData['contactNumber'];

        $nGuest->save();
    }
    public function updateEmail(Request $request)
    {


        $guest = Auth::guard('api')->user();
        if (!$guest) {
            return response()->json(['message' => 'Unauthorized'], 200);
        }


        $validatedData = $request->validate([
            'email' => 'required|string|max:255',
            'password' => 'required|string|max:255'
        ]);


        if (!Hash::check($validatedData['password'], $guest->Password)) {
            return response()->json(['message' => 'Invalid password'], 200);
        }

        $nGuest = Guest::find($guest->GuestId);

        $nGuest->EmailAddress = $validatedData['email'];

        $nGuest->save();
    }
    public function updatePassword(Request $request)
    {

        $guest = Auth::guard('api')->user();
        if (!$guest) {
            return response()->json(['message' => 'Unauthorized'], 200);
        }


        $validatedData = $request->validate([
            'newPassword' => 'required|string|max:255',
            'oldPassword' => 'required|string|max:255',
            'confirmPassword' => 'required|string|max:255'
        ]);

        if (!Hash::check($validatedData['oldPassword'], $guest->Password)) {
            return response()->json(['error' => 'Invalid password'], 200);
        }


        if ($validatedData['newPassword'] != $validatedData['confirmPassword']) {
            return response()->json(['error' => 'Passwords do not match'], 200);
        }
        $nGuest = Guest::find($guest->GuestId);
        $nGuest->Password = bcrypt($validatedData['newPassword']);

        $nGuest->save();

        return response()->json(['message' => 'Password updated successfully'], 200);
    }


    public function getPaymentInformation(Request $request)
    {
        $guest = Auth::guard('api')->user();

        $request->validate([
            'reference_number' => 'required|string'
        ]);

        if (!$guest) {
            return response()->json(['error' => 'Unauthorized'], 200);
        }

        $reservation = Reservation::with('payments')->whereHas('payments', function ($query) use ($request) {
            $query->where('ReferenceNumber', $request->reference_number);
        })->first();



        if (!$reservation) {
            return response()->json(['error' => 'Payment not found'], 200);
        }

        return response()->json($reservation);
    }

    public function verifyLogin(Request $request)
    {
        $guest = Auth::guard('api')->user();


        if (!$guest) {
            return response()->json(['message' => 'Unauthorized Access.'], 200);
        }


        $request->validate([
            'type' => 'required|string',
        ]);

        $type = $request->type;


        $otpCode = random_int(100000, 999999);

        if ($type === 'SMS') {
            $message = "Dear user, your one-time verification code is: $otpCode. Please enter this code to complete your verification. Do not share this code with anyone.";

            $response = Http::post('https://nasa-ph.com/api/send-sms', [
                'phone_number' => $guest->ContactNumber,
                'message' => $message
            ]);

            if ($response->successful()) {
                return response()->json(['message' => 'Verification code has been sent via SMS.', 'otp' => $otpCode], 200);
            } else {
                return response()->json(['message' => 'Failed to send the verification code via SMS. Please try again later.'], 500);
            }
        } elseif ($type === 'EMAIL') {
            $subject = 'Your One-Time Verification Code';
            $body = "Dear user,\n\nYour one-time verification code is: $otpCode.\nPlease enter this code to complete your verification. Do not share this code with anyone.\n\nThank you.";


            try {
                Mail::to($guest->EmailAddress)->send(new VerificationMail($body));
                return response()->json(['message' => 'Verification code has been sent via email.', 'otp' => $otpCode], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Failed to send the verification code via email. Please try again later.', 'error' => $e->getMessage()], 200);
            }
        } else {

            return response()->json(['message' => 'Invalid verification type specified.'], 400);
        }

        return response()->json(['message' => 'Unauthorized access.'], 401);
    }

    public function getPaymentHistory(Request $request)
    {
        $guest = Auth::guard('api')->user();

        $request->validate([
            'ReservationId' => 'required|string'
        ]);

        if (!$guest) {
            return response()->json(['message' => 'Unauthorized Access.'], 200);
        }

        $payments = Payment::where('GuestId', $guest->GuestId)->where('ReservationId', $request->ReservationId)->get();

        return response()->json($payments);
    }


    public function uploadProofPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|integer|exists:payments,PaymentId',
            'proof_image' => 'required|file|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $fileData = file_get_contents($request->file('proof_image')->getRealPath());

            $payment = Payment::where('PaymentId', $request->input('payment_id'))->first();

            if ($payment) {
                $payment->Attachment = $fileData;
                $payment->save();

                return response()->json([
                    'message' => 'Proof of payment uploaded successfully!',
                    'PaymentId' => $payment->PaymentId
                ], 200);
            } else {
                return response()->json(['message' => 'Payment record not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while uploading the proof of payment.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function addPayment(Request $request)
    {

        $validatedData = $request->validate([
            "reservation_id" => "required|exists:reservations,ReservationId",
            "payment_option" => "required|in:full,down",
        ]);


        $guest = Auth::guard('api')->user();

        if (!$guest) {
            return response()->json(['message' => 'Unauthorized Access.'], 401);
        }

        $reservation = Reservation::where("ReservationId", $validatedData['reservation_id'])->first();

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found.'], 404);
        }


        $guestDetails = Guest::where("GuestId", $guest->GuestId)->first();

        if (!$guestDetails) {
            return response()->json(['message' => 'Guest details not found.'], 404);
        }



        if (!$reservation || !$guest) {
            return response()->json(['error' => 'Invalid reservation or guest.'], 404);
        }

        // Payment configuration
        $this->apiKey = 'c2tfdGVzdF80OE1nWVk3U0dLdDY5dkVQZnRnZGpmS286';

        try {
            if ($validatedData['payment_option'] === 'down') {
                $partialPaymentAmount = $reservation->TotalCost * 0.30; // Example: 30% downpayment

                $payment = $reservation->payments()->create([
                    'GuestId' => $guest->GuestId,
                    'AmountPaid' => $partialPaymentAmount,
                    'DateCreated' => now()->toDateString(),
                    'TimeCreated' => now()->toTimeString(),
                    'Status' => 'Pending',
                    'PaymentType' => 'Gcash',
                    'ReferenceNumber' => $this->generateReferenceNumber(),
                    'Purpose' => "Room Reservation Down Payment",
                ]);

                $response = $this->createCheckoutSession($payment, $guest, $partialPaymentAmount, "Room Reservation Partial Payment");
            } else {
                $totalCost = $reservation->TotalCost;
                $payment = $reservation->payments()->create([
                    'GuestId' => $guest->GuestId,
                    'AmountPaid' => $totalCost,
                    'DateCreated' => now()->toDateString(),
                    'TimeCreated' => now()->toTimeString(),
                    'Status' => 'Pending',
                    'PaymentType' => 'Gcash',
                    'ReferenceNumber' => $this->generateReferenceNumber(),
                    'Purpose' => "Room Reservation Full Payment",
                ]);

                $response = $this->createCheckoutSession($payment, $guest, $totalCost, "Room Reservation Full Payment");
            }

            if ($response->successful()) {
                return response()->json($response->json(), 200);
            } else {
                return response()->json([
                    'error' => $response->body(),
                    'status' => $response->status()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function createCheckoutSession($payment, $guest, $amount, $description)
    {
        $reservation = $payment->reservation;
        $data = [
            'data' => [
                'attributes' => [
                    'cancel_url' => url('/cancel/' . $payment->ReferenceNumber),
                    'success_url' => url('/success/' . $payment->ReferenceNumber),
                    'billing' => [
                        'name' => $guest->FirstName . ' ' . $guest->LastName,
                        'email' => $guest->EmailAddress,
                        'phone' => $guest->ContactNumber,
                    ],
                    'send_email_receipt' => true,
                    'show_description' => true,
                    'show_line_items' => true,
                    'description' => $description,
                    'line_items' => [
                        [
                            'currency' => 'PHP',
                            'amount' => (int)($amount * 100),
                            'description' => $description,
                            'name' => $reservation->roomNumber->room->RoomType,
                            'quantity' => 1,
                        ],
                    ],
                    'payment_method_types' => ['gcash'],
                    'reference_number' => $payment->ReferenceNumber,
                ]
            ]
        ];

        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . $this->apiKey,
        ])->post('https://api.paymongo.com/v1/checkout_sessions', $data);
    }
}
