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
        Schema::create('tb_manufacturing_order', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedBigInteger('id_produk');
            $table->foreign('id_produk')->references('id')->on('tb_produk')->onDelete('cascade');
            $table->unsignedBigInteger('id_bom');
            $table->foreign('id_bom')->references('id')->on('tb_bom')->onDelete('cascade');
            $table->string('kode_mo')->unique();
            $table->integer('kuantitas_produk');
            $table->enum('status', ['draft', 'confirmed', 'in_progress', 'done', 'canceled'])->default('draft');
            $table->date('tanggal_produksi');
            $table->date('tanggal_deadline');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_manufacturing_order');
    }
};
