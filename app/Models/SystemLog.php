<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasFactory;
    protected $table = 'logs';
    protected $fillable = ['log', 'action', 'time_created', 'date_created'];
    public function scopeSearch($query, $value)
    {
        return $query->where('log', 'like', '%' . $value . '%')
            ->orWhere('action', 'like', '%' . $value . '%')
            ->orWhere('date_created', 'like', '%' . $value . '%');
    }


}
