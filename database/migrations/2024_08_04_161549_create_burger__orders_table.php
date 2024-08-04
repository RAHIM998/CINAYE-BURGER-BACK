<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('burger__orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Burger::class)->constrained();
            $table->foreignIdFor(\App\Models\Orders::class)->constrained();
            $table->string('unitPrice');
            $table->string('quantity');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('burger__orders');
    }
};
