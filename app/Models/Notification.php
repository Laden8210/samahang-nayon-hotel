<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'status',
        'guest_id',
        'isForGuest',
    ];
    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }


}
