<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    protected $primaryKey = 'PromotionId';

    public $timestamps = false;
    protected $fillable = [
        'Promotion',
        'Description',
        'Discount',
        'StartDate',
        'EndDate',
        'DateCreated'
    ];

    public function discountedRooms()
    {
        return $this->hasMany(DiscountedRoom::class, 'PromotionId');
    }

    public function scopeSearch($query, $value)
    {
        return $query->where('Promotion', 'like', '%' . $value . '%')
            ->orWhere('Description', 'like', '%' . $value . '%')
            ->orWhere('Discount', 'like', '%' . $value . '%')
            ->orWhere('StartDate', 'like', '%' . $value . '%')
            ->orWhere('EndDate', 'like', '%' . $value . '%');
    }
}
