<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PengaduanExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PengaduanController extends Controller
{
    public function create()
    {
        return view('pengaduan.create');
    }
    public function index()
    {
        $pengaduans = Pengaduan::latest()->get();

        return view('pengaduan.index', compact('pengaduans'));
    }

public function tracking(Request $request)
{
    $pengaduan = null;

    if ($request->has('no_ticket')) {

        $request->validate([
            'no_ticket' => 'required',
        ]);

        $pengaduan = Pengaduan::where(
            'no_ticket',
            strtoupper(trim($request->no_ticket))
        )->first();

        if (!$pengaduan) {
            return redirect()
                ->route('pengaduan.tracking')
                ->with('error', 'Nomor ticket tersebut tidak ditemukan.');
                }
    }

    return view('pengaduan.tracking', compact('pengaduan'));
}


public function admin(Request $request)
{
    // Query data pengaduan
    $query = Pengaduan::query();

    // Pencarian di seluruh data
    if ($request->filled('search')) {
        $search = trim($request->search);

        $query->where(function ($q) use ($search) {
            $q->where('nama_pelapor', 'like', "%{$search}%")
              ->orWhere('email_pelapor', 'like', "%{$search}%")
              ->orWhere('no_ticket', 'like', "%{$search}%")
              ->orWhere('no_hp', 'like', "%{$search}%")
              ->orWhere('id', 'like', "%{$search}%");
        });
    }

    // Filter berdasarkan status
    if ($request->filled('status')) {
        $status = $request->status;

        $statusValid = [
            'Pengajuan',
            'Verifikasi',
            'Pendalaman',
            'Tindak Lanjut',
            'Selesai',
        ];

        if (in_array($status, $statusValid, true)) {
            $query->where('status', $status);
        }
    }
    
    // Validasi rentang tanggal
    $request->validate([
        'tanggal_mulai' => 'nullable|date',
        'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_mulai',
    ]);

    // Filter tanggal mulai
    if ($request->filled('tanggal_mulai')) {
        $query->whereDate(
            'created_at',
            '>=',
            $request->tanggal_mulai
        );
    }

    // Filter tanggal akhir
    if ($request->filled('tanggal_akhir')) {
        $query->whereDate(
            'created_at',
            '<=',
            $request->tanggal_akhir
        );
    }


    // Pagination tetap 10 data per halaman
    $pengaduans = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

    // Statistik dihitung dari seluruh database
    $totalPengaduan = Pengaduan::count();

    $pengajuanBaru = Pengaduan::whereIn('status', [
        'Pengajuan',
    ])->count();

    $pengaduanSelesai = Pengaduan::where(
        'status',
        'Selesai'
    )->count();

    $sedangDiproses = $totalPengaduan
        - $pengajuanBaru
        - $pengaduanSelesai;

   
    // Pengaturan grafik
    $grafikMode = $request->query('grafik_mode', 'bulanan');

    if (!in_array($grafikMode, ['bulanan', 'tahunan'], true)) {
        $grafikMode = 'bulanan';
    }

    $grafikTahun = (int) $request->query(
        'grafik_tahun',
        now()->year
    );

    if ($grafikTahun < 2000 || $grafikTahun > now()->year) {
        $grafikTahun = now()->year;
    }

    $kategoriList = [
        'Kekerasan',
        'Bullying',
        'Diskriminasi',
        'Pelecehan Seksual',
        'Lainnya',
    ];

    $warnaKategori = [
        '#7C3AED',
        '#EC4899',
        '#3B82F6',
        '#F59E0B',
        '#14B8A6',
    ];

    $dataGrafik = Pengaduan::query()
        ->whereNotNull('kategori_pengaduan')
        ->whereIn('kategori_pengaduan', $kategoriList)
        ->whereYear('created_at', '>=', $grafikMode === 'bulanan'
            ? $grafikTahun
            : max(2000, now()->year - 4))
        ->whereYear('created_at', '<=', $grafikTahun)
        ->get(['kategori_pengaduan', 'created_at']);

    if ($grafikMode === 'bulanan') {
        $labelGrafik = [
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des',
        ];

        $jumlahKategori = [];

        foreach ($kategoriList as $kategori) {
            $jumlahKategori[$kategori] = array_fill(1, 12, 0);
        }

        foreach ($dataGrafik as $item) {
            $bulan = (int) $item->created_at->format('n');
            $jumlahKategori[$item->kategori_pengaduan][$bulan]++;
        }

        $datasetsGrafik = [];

        foreach ($kategoriList as $index => $kategori) {
            $datasetsGrafik[] = [
                'label' => $kategori,
                'data' => array_values($jumlahKategori[$kategori]),
                'backgroundColor' => $warnaKategori[$index],
            ];
        }
    } else {
        $tahunAwal = max(2000, now()->year - 4);
        $labelGrafik = range($tahunAwal, $grafikTahun);

        $jumlahKategori = [];

        foreach ($kategoriList as $kategori) {
            $jumlahKategori[$kategori] = array_fill_keys(
                $labelGrafik,
                0
            );
        }

        foreach ($dataGrafik as $item) {
            $tahun = (int) $item->created_at->format('Y');

            if (isset($jumlahKategori[$item->kategori_pengaduan][$tahun])) {
                $jumlahKategori[$item->kategori_pengaduan][$tahun]++;
            }
        }

        $datasetsGrafik = [];

        foreach ($kategoriList as $index => $kategori) {
            $datasetsGrafik[] = [
                'label' => $kategori,
                'data' => array_values($jumlahKategori[$kategori]),
                'backgroundColor' => $warnaKategori[$index],
            ];
        }
    }

    return view('pengaduan.admin', compact(
        'pengaduans',
        'totalPengaduan',
        'pengajuanBaru',
        'pengaduanSelesai',
        'sedangDiproses',
        'grafikMode',
        'grafikTahun',
        'labelGrafik',
        'datasetsGrafik'
    ));

}

public function exportExcel(Request $request)
{
    return Excel::download(
        new PengaduanExport(
            $request->query('search'),
            $request->query('status'),
            $request->query('tanggal_mulai'),
            $request->query('tanggal_akhir'),
        ),
        'laporan-pengaduan-psga.xlsx'
    );
}

public function exportPdf(Request $request)
{
    $query = Pengaduan::query();

    if ($request->filled('search')) {
        $search = trim($request->search);

        $query->where(function ($q) use ($search) {
            $q->where('nama_pelapor', 'like', "%{$search}%")
                ->orWhere('email_pelapor', 'like', "%{$search}%")
                ->orWhere('no_ticket', 'like', "%{$search}%")
                ->orWhere('no_hp', 'like', "%{$search}%")
                ->orWhere('id', 'like', "%{$search}%");
        });
    }

    $statusValid = [
        'Pengajuan',
        'Verifikasi',
        'Pendalaman',
        'Tindak Lanjut',
        'Selesai',
    ];

    if (
        $request->filled('status') &&
        in_array($request->status, $statusValid, true)
    ) {
        $query->where('status', $request->status);
    }

    
    // Filter tanggal mulai
    if ($request->filled('tanggal_mulai')) {
        $query->whereDate(
            'created_at',
            '>=',
            $request->tanggal_mulai
        );
    }

    // Filter tanggal akhir
    if ($request->filled('tanggal_akhir')) {
        $query->whereDate(
            'created_at',
            '<=',
            $request->tanggal_akhir
        );
    }


    $pengaduans = $query->latest()->get();

    $pdf = Pdf::loadView(
        'pengaduan.laporan-pdf',
        compact('pengaduans')
    )->setPaper('a4', 'landscape');

    return $pdf->download('laporan-pengaduan-psga.pdf');
}

public function edit($id)
{
    $pengaduan = Pengaduan::findOrFail($id);

    return view('pengaduan.edit', compact('pengaduan'));
}

public function update(Request $request, $id)
{
    $pengaduan = Pengaduan::findOrFail($id);

    $request->validate([
        'status' => 'required|in:Pengajuan,Verifikasi,Pendalaman,Tindak Lanjut,Selesai',
        'tanggapan' => 'nullable',
    ]);

    $pengaduan->update([
        'status' => $request->status,
        'tanggapan' => $request->tanggapan,
    ]);

    return redirect()
        ->route('pengaduan.admin')
        ->with('success', 'Pengaduan berhasil diperbarui.');
}


public function store(Request $request)
{
    $validated = $request->validate([
        'nama_pelapor' => 'required|string|max:255',
        'terlapor' => 'required|string|max:255',
        'email_pelapor' => 'required|email',
        'no_hp' => 'required|string|max:30',
        'fakultas' => 'required|string|max:255',
        'kategori_pengaduan' => 'required|in:Kekerasan,Diskriminasi,Bullying,Pelecehan Seksual,Lainnya',
        'isi_pengaduan' => 'required|string',
    ]);

    // Simpan pengaduan dan kategori
    $pengaduan = Pengaduan::create([
        'nama_pelapor' => $validated['nama_pelapor'],
        'terlapor' => $validated['terlapor'],
        'email_pelapor' => $validated['email_pelapor'],
        'no_hp' => $validated['no_hp'],
        'fakultas' => $validated['fakultas'],
        'kategori_pengaduan' => $validated['kategori_pengaduan'],

        // Pertahankan kolom lama agar tampilan yang menggunakannya
        // tetap menampilkan kategori seperti sebelumnya.
        'judul_pengaduan' => $validated['kategori_pengaduan'],

        'isi_pengaduan' => $validated['isi_pengaduan'],
        'status' => 'Pengajuan',
    ]);

    // Buat nomor tiket otomatis
    $pengaduan->no_ticket = 'PSGA-' .
        str_pad($pengaduan->id, 3, '0', STR_PAD_LEFT);

    $pengaduan->save();

    return redirect()
        ->back()
        ->with(
            'success',
            'Pengaduan berhasil dikirim. Nomor Tiket Anda: ' .
            $pengaduan->no_ticket
        );
}
}