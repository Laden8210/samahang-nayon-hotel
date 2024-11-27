<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $table = 'messages';
    protected $primaryKey = 'MessageId';
    public $timestamps = false;
    protected $fillable = [
        'GuestId',
        'EmployeeId',
        'IsReadEmployee',
        'IsReadGuest',
        'Message',
        'DateSent',
        'TimeSent',
        'isGuestMessage',
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'GuestId', 'GuestId');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'EmployeeId', 'EmployeeId');
    }
}
