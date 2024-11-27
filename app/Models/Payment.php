<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $primaryKey = 'PaymentId';
    protected $table = 'payments';
    public $timestamps = false;


    protected $fillable = [
        'GuestId',
        'ReservationId',
        'AmountPaid',
        'DateCreated',
        'TimeCreated',
        'Status',
        'PaymentType',
        'ReferenceNumber',
        'Purpose',
        'Attachment'
    ];

    public function scopeSearch($query, $val)
    {
        return $query->where('ReferenceNumber', 'like', '%'.$val.'%')
            ->orWhere('Purpose', 'like', '%'.$val.'%')
            ->orWhere('PaymentType', 'like', '%'.$val.'%')
            ->orWhere('Status', 'like', '%'.$val.'%')
            ->orWhere('AmountPaid', 'like', '%'.$val.'%')
            ->orWhere('DateCreated', 'like', '%'.$val.'%')
            ->orWhere('TimeCreated', 'like', '%'.$val.'%')
            ->orWhereHas('guest', function ($query) use ($val) {
                $query->where('FirstName', 'like', '%'.$val.'%')
                    ->orWhere('LastName', 'like', '%'.$val.'%')
                    ->orWhere('MiddleName', 'like', '%'.$val.'%');
            });

    }

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'GuestId');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'ReservationId');
    }
}
