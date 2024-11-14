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
        Schema::create('tb_manufacturing_order_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_manufacturing_order')->constrained('tb_manufacturing_order')->onDelete('cascade');
            $table->foreignId('id_bahanbaku')->constrained('tb_bahanbaku')->onDelete('cascade');
            $table->integer('reserved');
            $table->integer('consumed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_manufacturing_order_detail');
    }
};
