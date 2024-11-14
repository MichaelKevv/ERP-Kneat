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
        Schema::create('tb_vendor', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->enum('tipe_vendor', ['perusahaan', 'perorangan']);
            $table->string('nama');
            $table->string('alamat');
            $table->string('npwp')->nullable();
            $table->string('email');
            $table->string('no_hp');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_vendor');
    }
};
