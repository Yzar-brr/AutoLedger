<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehiclePhoto extends Model
{
    use HasFactory;

    // Précise le nom de la table car elle contient un underscore
    protected $table = 'vehicle_photos';

    protected $fillable = [
        'vehicle_id',
        'path',
    ];

    /**
     * Relation inverse : La photo apparti²ent à un véhicule
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
