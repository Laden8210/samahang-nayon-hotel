<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationAmenities extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'reservationamenities';

    protected $primaryKey = 'ReservationAmenitiesId';

    protected $fillable = [
        'ReservationId',
        'AmenitiesId',
        'Quantity',
        'TotalCost'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'ReservationId');
    }

    public function amenity()
    {
        return $this->belongsTo(Amenities::class, 'AmenitiesId');
    }
}
