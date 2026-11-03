<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $fillable = [
        'no_ticket',
        'nama_pelapor',
        'terlapor',
        'email_pelapor',
        'no_hp',
        'fakultas',
        'kategori_pengaduan',
        'judul_pengaduan',
        'isi_pengaduan',
        'status',
        'tanggapan',
    ];
}