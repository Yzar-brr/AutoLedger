<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenancePlan extends Model
{
    protected $fillable = ['truck_id', 'user_id', 'check_date', 'data'];

    // On cast le JSON en array automatiquement pour PHP
    protected $casts = [
        'data' => 'array',
        'check_date' => 'date'
    ];

    public function truck() {
        return $this->belongsTo(Truck::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}