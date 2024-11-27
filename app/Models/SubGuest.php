<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubGuest extends Model
{
    use HasFactory;
    protected $primaryKey = 'SubGuestId';
    protected $table = 'subguest';
    public $timestamps = false;
    protected $fillable = [
        'ReservationId',
        'FirstName',
        'LastName',
        'MiddleName',
        'ContactNumber',
        'EmailAddress',
        'Birthdate',
        'Gender'

    ];

}
