<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('berita', function (Blueprint $table) {
            $table->longText('ringkasan')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('berita', function (Blueprint $table) {
            $table->string('ringkasan', 500)->nullable()->change();
        });
    }
};