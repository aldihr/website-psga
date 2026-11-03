<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table = 'berita';

    protected $fillable = [
        'judul',
        'slug',
        'ringkasan',
        'isi',
        'gambar',
        'kategori',
        'status',
        'tanggal_terbit',
    ];

    protected $casts = [
        'tanggal_terbit' => 'datetime',
    ];
}
