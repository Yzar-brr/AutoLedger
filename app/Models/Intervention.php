<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;

    protected $fillable = [
    'truck_id',
    'date',
    'technician',
    'price',
    'mileage',
    'duration',
    'description',
    'photos',
];

    protected $casts = ['photos' => 'array'];

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }
}
