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
        Schema::create('tb_produk', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('nama_produk');
            $table->unsignedBigInteger('id_kategori');
            $table->foreign('id_kategori')->references('id')->on('tb_kategori')->onDelete('cascade');
            $table->integer('harga_jual');
            $table->integer('biaya_produk');
            $table->string('exp');
            $table->string('internal_reference')->nullable();
            $table->string('barcode');
            $table->string('satuan')->nullable();
            $table->integer('pajak')->nullable()->default(0);
            $table->string('note')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_produk');
    }
};
