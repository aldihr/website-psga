<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karya_ilmiah', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_publikasi', 100);
            $table->string('nama_penulis', 255);
            $table->string('nama_karya', 255);
            $table->text('deskripsi')->nullable();
            $table->string('link', 2048)->nullable();
            $table->year('tahun_publikasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karya_ilmiah');
    }
};
