<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryaIlmiah extends Model
{
    use HasFactory;

    protected $table = 'karya_ilmiah';

    protected $fillable = [
        'jenis_publikasi',
        'nama_penulis',
        'nama_karya',
        'deskripsi',
        'link',
        'tahun_publikasi',
    ];
}
