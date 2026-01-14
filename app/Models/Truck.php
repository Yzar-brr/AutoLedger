<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    use HasFactory;

    // Ajoute ces trois champs ici
    protected $fillable = ['client_id', 'brand', 'plate_number'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }

    public function maintenancePlans()
    {
        return $this->hasMany(MaintenancePlan::class)->latest();
    }
}
