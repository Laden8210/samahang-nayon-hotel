<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomPictures;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;


class RoomController extends Controller
{

    public function index()
    {
        return view('admin.room.index');
    }

    public function addRoom()
    {
        return view('admin.room.add');
    }

    public function updateRoom($roomId)
    {
        try {
            $decryptedId = Crypt::decrypt($roomId);
            $room = Room::findOrFail($decryptedId);
            return view('admin.room.update', compact('room'));
        } catch (DecryptException $e) {
            return redirect()->route('admin.room.index')->with('error', 'Invalid Room ID.');
        }
    }

    public function viewRoom($roomId){
        try {
            $decryptedId = Crypt::decrypt($roomId);
            $room = Room::findOrFail($decryptedId);
            return view('admin.room.view', compact('room'));
        } catch (DecryptException $e) {
            return redirect()->route('admin.room.index')->with('error', 'Invalid Room ID.');
        }
    }

    public function receptionistIndex()
    {
        return view('admin.room.receptionist-room');
    }
}
