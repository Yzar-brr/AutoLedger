<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;

    protected $fillable = ['truck_id', 'date', 'technician', 'price', 'duration', 'description', 'photos'];
    protected $casts = ['photos' => 'array']; // Important pour les images
    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }
}
