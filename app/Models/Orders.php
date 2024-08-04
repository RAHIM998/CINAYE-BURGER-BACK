<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orders extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'dateOrder',
        'amountOrder',
        'addressLivraison',
        'status',

    ];

    // Relation avec les utilisateurs
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation many-to-many avec les burgers
    public function burgers()
    {
        return $this->belongsToMany(Burger::class)->withPivot('unitPrice', 'quantity');
    }

    // Relation avec les paiements
    public function payments()
    {
        return $this->hasOne(Payment::class);
    }
}
