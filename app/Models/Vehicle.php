<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    // Autorise le remplissage de tous les champs nécessaires
    protected $fillable = [
        'user_id',
        'model',
        'registration',
        'status',
        'purchase_price',
        'selling_price',
        'image_path',
    ];

    /**
     * Logique automatique du modèle
     */
    protected static function booted(): void
    {
        // SCOPE : L'utilisateur ne voit que ses propres véhicules
        static::addGlobalScope('user_filter', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where('user_id', auth()->id());
            }
        });

        // HOOK : Attribue automatiquement le user_id à la création
        static::creating(function ($vehicle) {
            if (auth()->check()) {
                $vehicle->user_id = auth()->id();
            }
        });
    }

    /**
     * Relation : Un véhicule appartient à un utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Un véhicule peut avoir plusieurs frais (expenses)
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Relation : Un véhicule peut avoir plusieurs photos secondaires
     */
    public function photos(): HasMany
    {
        return $this->hasMany(VehiclePhoto::class);
    }
}
