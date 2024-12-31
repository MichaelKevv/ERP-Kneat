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
        Schema::create('tb_rfq', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedBigInteger('id_vendor');
            $table->foreign('id_vendor')->references('id')->on('tb_vendor')->onDelete('cascade');
            $table->string('kode_rfq')->unique();
            $table->date('tanggal_order');
            $table->enum('status', ['draft', 'rfq_sent', 'rfq_approved', 'purchase_order'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_rfq');
    }
};
