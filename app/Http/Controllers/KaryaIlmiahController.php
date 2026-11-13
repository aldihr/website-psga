<?php

namespace App\Http\Controllers;

use App\Models\KaryaIlmiah;
use Illuminate\Http\Request;

class KaryaIlmiahController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim($request->input('q', ''));
        $jenisPublikasiTerpilih = trim($request->input('jenis_publikasi', ''));
        $tahunTerpilih = trim($request->input('tahun_publikasi', ''));

        $query = KaryaIlmiah::query();

        if ($keyword !== '') {
            $query->where(function ($search) use ($keyword) {
                $search->where('nama_karya', 'like', '%' . $keyword . '%')
                    ->orWhere('nama_penulis', 'like', '%' . $keyword . '%')
                    ->orWhere('jenis_publikasi', 'like', '%' . $keyword . '%')
                    ->orWhere('deskripsi', 'like', '%' . $keyword . '%');
            });
        }

        if ($jenisPublikasiTerpilih !== '') {
            $query->where('jenis_publikasi', $jenisPublikasiTerpilih);
        }

        if ($tahunTerpilih !== '') {
            $query->where('tahun_publikasi', $tahunTerpilih);
        }

        $jenisPublikasi = KaryaIlmiah::query()
            ->whereNotNull('jenis_publikasi')
            ->where('jenis_publikasi', '<>', '')
            ->distinct()
            ->orderBy('jenis_publikasi')
            ->pluck('jenis_publikasi');

        $tahunPublikasi = KaryaIlmiah::query()
            ->whereNotNull('tahun_publikasi')
            ->distinct()
            ->orderByDesc('tahun_publikasi')
            ->pluck('tahun_publikasi');

        $karyaIlmiah = $query
            ->orderByDesc('tahun_publikasi')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('karya-ilmiah.index', compact('karyaIlmiah', 'jenisPublikasi', 'tahunPublikasi'));
    }
}
