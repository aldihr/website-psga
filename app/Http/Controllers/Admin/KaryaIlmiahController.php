<?php

namespace App\Http\Controllers\Admin;

use App\Exports\KaryaIlmiahExporter;
use App\Http\Controllers\Controller;
use App\Models\KaryaIlmiah;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class KaryaIlmiahController extends Controller
{
    /**
     * Menampilkan daftar karya ilmiah.
     */
    public function index(Request $request)
    {
        $query = $this->filteredQuery($request);

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

        return view(
            'admin.karya-ilmiah.index',
            compact('karyaIlmiah', 'jenisPublikasi', 'tahunPublikasi')
        );
    }

    public function exportExcel(Request $request, KaryaIlmiahExporter $exporter)
    {
        $records = $this->filteredQuery($request)
            ->orderByDesc('tahun_publikasi')
            ->orderByDesc('id')
            ->get();
        $path = $exporter->excel($records);

        return response()->download(
            $path,
            $this->namaFileEkspor($request, 'xlsx'),
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        )->deleteFileAfterSend(true);
    }

    public function exportPdf(Request $request, KaryaIlmiahExporter $exporter)
    {
        $records = $this->filteredQuery($request)
            ->orderByDesc('tahun_publikasi')
            ->orderByDesc('id')
            ->get();
        $pdf = $exporter->pdf($records);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $this->namaFileEkspor($request, 'pdf') . '"',
        ]);
    }

    private function namaFileEkspor(Request $request, string $ekstensi): string
    {
        $tahun = $request->input('tahun_publikasi');
        $nama = 'Karya Ilmiah PSGA';

        if ($tahun !== null && $tahun !== '') {
            $nama .= '-' . $tahun;
        }

        $jenisPublikasi = trim((string) $request->input('jenis_publikasi', ''));
        if ($jenisPublikasi !== '') {
            $jenisUntukNamaFile = preg_replace('/[^\pL\pN ]/u', '', $jenisPublikasi);
            $jenisUntukNamaFile = preg_replace('/\s+/u', ' ', trim($jenisUntukNamaFile));
            $nama .= '-' . $jenisUntukNamaFile;
        }

        return $nama . ' (' . now('Asia/Jakarta')->format('d-m-Y') . ').' . $ekstensi;
    }

    private function filteredQuery(Request $request): Builder
    {
        $tahunAkhirRules = ['nullable', 'integer', 'min:1901', 'max:2155'];
        if ($request->filled('tahun_mulai')) {
            $tahunAkhirRules[] = 'gte:tahun_mulai';
        }

        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'jenis_publikasi' => ['nullable', 'string', 'max:100'],
            'tahun_publikasi' => ['nullable', 'integer', 'min:1901', 'max:2155'],
            'tahun_mulai' => ['nullable', 'integer', 'min:1901', 'max:2155'],
            'tahun_akhir' => $tahunAkhirRules,
        ]);

        $query = KaryaIlmiah::query();
        $keyword = trim($filters['q'] ?? '');

        if ($keyword !== '') {
            $query->where(function (Builder $search) use ($keyword) {
                $search->where('nama_karya', 'like', "%{$keyword}%")
                    ->orWhere('nama_penulis', 'like', "%{$keyword}%")
                    ->orWhere('jenis_publikasi', 'like', "%{$keyword}%")
                    ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }

        if (!empty($filters['jenis_publikasi'])) {
            $query->where('jenis_publikasi', $filters['jenis_publikasi']);
        }

        if (!empty($filters['tahun_publikasi'])) {
            $query->where('tahun_publikasi', $filters['tahun_publikasi']);
        }

        if (!empty($filters['tahun_mulai'])) {
            $query->where('tahun_publikasi', '>=', $filters['tahun_mulai']);
        }

        if (!empty($filters['tahun_akhir'])) {
            $query->where('tahun_publikasi', '<=', $filters['tahun_akhir']);
        }

        return $query;
    }

    /**
     * Menampilkan formulir tambah karya ilmiah.
     */
    public function create()
    {
        return view('admin.karya-ilmiah.create');
    }

    /**
     * Menyimpan karya ilmiah baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_karya' => 'required|string|max:255',
            'nama_penulis' => 'required|string|max:255',
            'jenis_publikasi_pilihan' => 'required|string|max:100',
            'jenis_publikasi_lainnya' => 'nullable|string|max:100',
            'tahun_publikasi' => 'required|integer|min:1901|max:2155',
            'deskripsi' => 'nullable|string',
            'link' => 'nullable|url|max:2048',
        ]);
        $data['jenis_publikasi'] = $data['jenis_publikasi_pilihan'];
        if ($data['jenis_publikasi_pilihan'] === 'Lainnya') {
            $jenisLainnya = $this->kapitalisasiAwalSetiapKata($data['jenis_publikasi_lainnya'] ?? '');
            $data['jenis_publikasi'] = $jenisLainnya !== '' ? $jenisLainnya : 'Lainnya';
        }
        unset($data['jenis_publikasi_pilihan'], $data['jenis_publikasi_lainnya']);

        $duplikat = $this->cariDuplikat($data['nama_karya'], $data['jenis_publikasi']);
        if ($duplikat && $request->input('konfirmasi_duplikat') !== '1') {
            return back()
                ->withInput()
                ->with('karyaIlmiahDuplikat', $this->ringkasanDuplikat($duplikat));
        }

        KaryaIlmiah::create($data);

        return redirect()
            ->route('admin.karya-ilmiah.index')
            ->with('success', 'Karya ilmiah berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir edit karya ilmiah.
     */
    public function edit(KaryaIlmiah $karyaIlmiah)
    {
        return view(
            'admin.karya-ilmiah.edit',
            compact('karyaIlmiah')
        );
    }

    /**
     * Memperbarui karya ilmiah.
     */
    public function update(
        Request $request,
        KaryaIlmiah $karyaIlmiah
    ) {
        $data = $request->validate([
            'nama_karya' => 'required|string|max:255',
            'nama_penulis' => 'required|string|max:255',
            'jenis_publikasi_pilihan' => 'required|string|max:100',
            'jenis_publikasi_lainnya' => 'nullable|string|max:100',
            'tahun_publikasi' => 'required|integer|min:1901|max:2155',
            'deskripsi' => 'nullable|string',
            'link' => 'nullable|url|max:2048',
        ]);
        $data['jenis_publikasi'] = $data['jenis_publikasi_pilihan'];
        if ($data['jenis_publikasi_pilihan'] === 'Lainnya') {
            $jenisLainnya = $this->kapitalisasiAwalSetiapKata($data['jenis_publikasi_lainnya'] ?? '');
            $data['jenis_publikasi'] = $jenisLainnya !== '' ? $jenisLainnya : 'Lainnya';
        }
        unset($data['jenis_publikasi_pilihan'], $data['jenis_publikasi_lainnya']);

        $duplikat = $this->cariDuplikat(
            $data['nama_karya'],
            $data['jenis_publikasi'],
            $karyaIlmiah->id
        );
        if ($duplikat && $request->input('konfirmasi_duplikat') !== '1') {
            return back()
                ->withInput()
                ->with('karyaIlmiahDuplikat', $this->ringkasanDuplikat($duplikat));
        }

        $karyaIlmiah->update($data);

        return redirect()
            ->route('admin.karya-ilmiah.index')
            ->with('success', 'Karya ilmiah berhasil diperbarui.');
    }

    private function kapitalisasiAwalSetiapKata(string $teks): string
    {
        $teks = trim($teks);

        return preg_replace_callback(
            '/(^|[\s\-\/])(\p{L})/u',
            static fn (array $cocok) => $cocok[1] . mb_strtoupper($cocok[2], 'UTF-8'),
            $teks
        );
    }

    private function cariDuplikat(string $judul, string $jenisPublikasi, ?int $kecualiId = null): ?KaryaIlmiah
    {
        $query = KaryaIlmiah::query()
            ->whereRaw('LOWER(nama_karya) = ?', [mb_strtolower(trim($judul), 'UTF-8')])
            ->whereRaw('LOWER(jenis_publikasi) = ?', [mb_strtolower(trim($jenisPublikasi), 'UTF-8')]);

        if ($kecualiId !== null) {
            $query->where('id', '<>', $kecualiId);
        }

        return $query->first();
    }

    private function ringkasanDuplikat(KaryaIlmiah $karyaIlmiah): array
    {
        $waktuDibuat = $karyaIlmiah->created_at;

        return [
            'id' => $karyaIlmiah->id,
            'nama_karya' => $karyaIlmiah->nama_karya,
            'nama_penulis' => $karyaIlmiah->nama_penulis,
            'jenis_publikasi' => $karyaIlmiah->jenis_publikasi,
            'tahun_publikasi' => $karyaIlmiah->tahun_publikasi,
            'deskripsi' => $karyaIlmiah->deskripsi,
            'created_at_local' => $waktuDibuat?->copy()->setTimezone('Asia/Jakarta')->format('d/m/Y H:i'),
            'created_at_utc' => $waktuDibuat?->copy()->setTimezone('UTC')->format('d/m/Y H:i'),
        ];
    }

    /**
     * Menghapus karya ilmiah.
     */
    public function destroy(KaryaIlmiah $karyaIlmiah)
    {
        $karyaIlmiah->delete();

        return redirect()
            ->route('admin.karya-ilmiah.index')
            ->with('success', 'Karya ilmiah berhasil dihapus.');
    }
}
