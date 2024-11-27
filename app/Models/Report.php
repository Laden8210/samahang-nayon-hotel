<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'report';
    protected $primaryKey = 'ReportId';
    public $timestamps = false;

    protected $fillable = [
        'ReportName',
        'EmployeeId',
        'Date',
        'type',
        'EndDate',
        'CreatedAt',
        'GuestId'
    ];

    public function scopeSearch($query, $value)
    {
        return $query->where(function ($query) use ($value) {
            $query->where('ReportName', 'like', '%' . $value . '%')
                  ->orWhere('type', 'like', '%' . $value . '%')
                  ->orWhereHas('employee', function ($q) use ($value) {
                      $q->where('FirstName', 'like', '%' . $value . '%'); // Assuming Employee has a 'name' field
                  });
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'EmployeeId');
    }
}
