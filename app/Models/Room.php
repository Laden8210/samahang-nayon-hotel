<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $primaryKey = 'RoomId';
    public $timestamps = false;

    protected $dates = ['deleted_at'];


    protected $fillable = [
        'Description',
        'RoomType',
        'RoomPrice',
        'Capacity'
    ];

    public function scopeSearch($query, $value){
        return $query->where('Description', 'like', '%'.$value.'%')
        ->orWhere('RoomType', 'like', '%'.$value.'%')
        ->orWhere('RoomPrice', 'like', '%'.$value.'%')
        ->orWhere('Capacity', 'like', '%'.$value.'%');
    }

    public function roomPictures()
    {
        return $this->hasMany(RoomPictures::class, 'RoomId', 'RoomId');
        }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'RoomId');
    }

    public function discountedRooms()
    {
        return $this->hasMany(DiscountedRoom::class, 'RoomId');
    }

    public function roomNumber()
    {
        return $this->hasMany(RoomNumber::class, 'RoomId');
    }
}
