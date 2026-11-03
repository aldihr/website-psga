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
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();

    $table->string('nama_pelapor');
    $table->string('email_pelapor');
    $table->string('no_hp');
    $table->string('judul_pengaduan');
    $table->text('isi_pengaduan');
    $table->string('status')->default('Menunggu');
    $table->text('tanggapan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};
