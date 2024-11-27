<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomPictures extends Model
{
    use HasFactory;

    protected $primaryKey = 'RoomPictureId';
    protected $table = 'roompictures';
    public $timestamps = false;

    protected $fillable = [
        'RoomId',
        'PictureFile',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'RoomId', 'RoomId');
    }
}
