<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenities extends Model
{
    use HasFactory;

    protected $primaryKey = 'AmenitiesId';
    public $timestamps = false;

    protected $fillable = [
        'Name',
        'Price'
    ];

    public function scopeSearch($query, $value)
    {
        return $query->where('Name', 'like', '%' . $value . '%')
            ->orWhere('Price', 'like', '%' . $value . '%');
    }


    public function reservationAmenities()
    {
        return $this->hasMany(ReservationAmenities::class, 'AmenitiesId');
    }

}
