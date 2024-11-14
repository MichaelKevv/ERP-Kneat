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
        Schema::create('tb_bom_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedBigInteger('id_bom');
            $table->foreign('id_bom')->references('id')->on('tb_bom')->onDelete('cascade');
            $table->unsignedBigInteger('id_bahanbaku');
            $table->foreign('id_bahanbaku')->references('id')->on('tb_bahanbaku')->onDelete('cascade');
            $table->float('kuantitas_bahan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_bom_detail');
    }
};
