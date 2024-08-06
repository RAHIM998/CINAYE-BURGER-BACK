<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Burger extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'name',
      'price',
      'quantity',
      'image',
      'description',
      'archived',
    ];


    // Relation many-to-many avec les commandes
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'burger__orders')->withPivot('quantity', 'unitPrice');
    }
}
