<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    // Ajoute 'user_id' ici
    protected $fillable = ['user_id', 'name', 'phone', 'contact_person'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trucks()
    {
        return $this->hasMany(Truck::class);
    }
}