<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $primaryKey = 'ReservationId';
    protected $table = 'reservations';
    public $timestamps = false;

    protected $fillable = [
        'GuestId',
        'room_number_id',
        'DateCreated',
        'TimeCreated',
        'DateCheckIn',
        'DateCheckOut',
        'TotalCost',
        'Status',
        'TotalAdult',
        'TotalChildren',
        'OriginalCost',
        'Discount',
        'Source',
        'DateCancelled',
        'DiscountType',
        'IdNumber',
        'penalty'
    ];

    public function scopeSearch($query, $value){

        return $query->whereHas('guest', function($query) use ($value){
            $query->where('FirstName', 'like', '%'.$value.'%')
                ->orWhere('LastName', 'like', '%'.$value.'%')

                ->orWhere('ContactNumber', 'like', '%'.$value.'%')
                ;
        })
        ->orWhere('DateCheckIn', 'like', '%'.$value.'%')
        ->orWhere('DateCheckOut', 'like', '%'.$value.'%')
        ->orWhere('TotalCost', 'like', '%'.$value.'%')
        ->orWhere('Status', 'like', '%'.$value.'%')
        ->orWhere('TotalAdult', 'like', '%'.$value.'%')
        ->orWhere('TotalChildren', 'like', '%'.$value.'%')
        ;
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'GuestId');
    }

    public function roomNumber()
    {
        return $this->belongsTo(RoomNumber::class, 'room_number_id')->withTrashed();
    }



    public function reservationAmenities()
    {
        return $this->hasMany(ReservationAmenities::class, 'ReservationId');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'ReservationId');
    }

    public function checkInOuts()
    {
        return $this->hasMany(CheckInOut::class, 'ReservationId');
    }

    public function subGuests(){
        return $this->hasMany(SubGuest::class, 'ReservationId');
    }
}
