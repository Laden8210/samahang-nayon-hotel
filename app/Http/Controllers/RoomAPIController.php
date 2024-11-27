<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use Carbon\Carbon;

use App\Models\DiscountedRoom;
use App\Models\Promotion;
use App\Models\Reservation;
use App\Models\RoomNumber;

class RoomAPIController extends Controller
{
    public function getRoom(Request $request)
    {
        // Parse check-in and check-out dates
        $checkIn = Carbon::parse($request->checkIn);
        $checkOut = Carbon::parse($request->checkOut);

        // Calculate total guests
        $adults = $request->adult;
        $children = $request->children;
        $totalGuests = $adults + $children;

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
        $availableRooms = RoomNumber::with('room')->whereHas('room', function ($query) use ($totalGuests) {
            $query->where('Capacity', '>=', $totalGuests);
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

        foreach ($availableRooms as $roomNumber) {
            $roomNumber->isBooked = in_array($roomNumber->room_number_id, $bookedRooms->toArray());
        }

        // Transform available rooms into a flat structure and filter out booked rooms
        $flatRooms = $availableRooms->filter(function ($roomNumber) {
            return !$roomNumber->isBooked; // Keep only rooms that are not booked
        })->map(function ($roomNumber) {
            return [
                'room_number_id' =>  $roomNumber->room_number_id, // Cast to string
                'room_number' => $roomNumber->room_number,
                'RoomId' =>  $roomNumber->room->RoomId,
                'discount' => $roomNumber->discount ?? null,
                'RoomType' => $roomNumber->room->RoomType,
                'Capacity' =>  $roomNumber->room->Capacity,
                'RoomPrice' => $roomNumber->room->RoomPrice,
                'Description' => $roomNumber->room->Description,
            ];
        });

        return response()->json($flatRooms->values()->all());
    }




    public function getImage(Request $request)
    {
        $room = Room::find($request->room_id);
        if ($room && $room->roomPictures->first()) {
            $picture = $room->roomPictures->first();
            $base64Image = base64_encode($picture->PictureFile);
            return response()->json([
                'image' => $base64Image,
                'mime_type' => $picture->mime_type
            ]);
        }
        return response()->json(['error' => 'Room or image not found'], 404);
    }


    public function searchRoom(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $test = 'test';

        $rooms = Room::all();

        return response()->json($rooms);
    }
}
