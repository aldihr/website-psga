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
    Schema::create('berita', function (Blueprint $table) {
        $table->id();
        $table->string('judul');
        $table->string('slug')->unique();
        $table->string('ringkasan', 500)->nullable();
        $table->longText('isi');
        $table->string('gambar')->nullable();
        $table->string('kategori')->nullable();
        $table->enum('status', ['draft', 'terbit'])
              ->default('draft');
        $table->timestamp('tanggal_terbit')->nullable();
        $table->timestamps();
    });
    }
};
