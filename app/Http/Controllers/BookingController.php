<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{

    public function index()
    {
        return view('admin.booking.index');
    }


    public function create()
    {
        return view('admin.booking.create');
    }


    public function bookingDetails(Request $request)
    {
        $ReservationId = $request->ReservationId;
        return view('admin.booking.booking-details', compact('ReservationId'));
    }



    public function checkInOut(){
        return view('admin.booking.check-in-out');
    }
}
