<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    /**
     * Les attributs qui peuvent être assignés en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = ['model', 'registration', 'status', 'image_path', 'purchase_price', 'selling_price'];
    public function photos() {
    return $this->hasMany(VehiclePhoto::class);
}

    public function expenses() {    return $this->hasMany(Expense::class);

    
}
}

