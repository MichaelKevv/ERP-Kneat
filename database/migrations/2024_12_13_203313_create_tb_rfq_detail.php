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
        Schema::create('tb_rfq_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedBigInteger('id_rfq');
            $table->foreign('id_rfq')->references('id')->on('tb_rfq')->onDelete('cascade');
            $table->unsignedBigInteger('id_bahanbaku');
            $table->foreign('id_bahanbaku')->references('id')->on('tb_bahanbaku')->onDelete('cascade');
            $table->integer('kuantitas');
            $table->integer('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_rfq_detail');
    }
};
