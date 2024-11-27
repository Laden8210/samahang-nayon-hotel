<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountedRoom extends Model
{
    use HasFactory;
    protected $primaryKey = 'DiscountedRoomId';
    public $table = 'discountedrooms';
    public $timestamps = false;


    protected $fillable = [
        'RoomId',
        'PromotionId'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'RoomId','RoomId');
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'PromotionId');
    }
}
