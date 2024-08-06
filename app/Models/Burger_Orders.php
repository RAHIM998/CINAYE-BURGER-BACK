<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Burger_Orders extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'burger_id',
        'order_id',
        'unitPrice',
        'quantity',
    ];
}
