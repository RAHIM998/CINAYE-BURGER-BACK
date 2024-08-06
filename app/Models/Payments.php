<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payments extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'orders_id',
        'amountOrder',
        'payment_date',
    ];

    // Relation avec les commandes
    public function orders()
    {
        return $this->belongsTo(Orders::class);
    }
}
