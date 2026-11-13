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

        // Urutkan berdasarkan ID dari terkecil ke terbesar.
        // Data baru akan muncul setelah data lama.
        $karyaIlmiah = $query
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.karya-ilmiah.index',
            compact('karyaIlmiah', 'jenisPublikasi', 'tahunPublikasi')
        );
    }

    public function exportExcel(Request $request, KaryaIlmiahExporter $exporter)
    {
        $records = $this->filteredQuery($request)->orderBy('id')->get();
        $path = $exporter->excel($records);

        return response()->download(
            $path,
            'karya-ilmiah-' . now()->format('Ymd-His') . '.xlsx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        )->deleteFileAfterSend(true);
    }

    public function exportPdf(Request $request, KaryaIlmiahExporter $exporter)
    {
        $records = $this->filteredQuery($request)->orderBy('id')->get();
        $pdf = $exporter->pdf($records);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="karya-ilmiah-' . now()->format('Ymd-His') . '.pdf"',
        ]);
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
            'jenis_publikasi' => 'required|string|max:100',
            'tahun_publikasi' => 'required|integer|min:1901|max:2155',
            'deskripsi' => 'nullable|string',
            'link' => 'nullable|url|max:2048',
        ]);

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
            'jenis_publikasi' => 'required|string|max:100',
            'tahun_publikasi' => 'required|integer|min:1901|max:2155',
            'deskripsi' => 'nullable|string',
            'link' => 'nullable|url|max:2048',
        ]);

        $karyaIlmiah->update($data);

        return redirect()
            ->route('admin.karya-ilmiah.index')
            ->with('success', 'Karya ilmiah berhasil diperbarui.');
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
