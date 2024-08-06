<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payments extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'order_id',
        'amountOrder',
        'payment_date',
    ];

    // Relation avec les commandes
    public function order()
    {
        return $this->belongsTo(Orders::class);
    }
}
