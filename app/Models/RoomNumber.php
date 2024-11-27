<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomNumber extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'room_number';
    protected $primaryKey = 'room_number_id';
    protected $fillable = ['room_number', 'RoomId'];

    protected $dates = ['deleted_at'];


    public function room()
    {
        return $this->belongsTo(Room::class, 'RoomId')->withTrashed();
    }
}
