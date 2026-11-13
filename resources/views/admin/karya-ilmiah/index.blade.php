<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Karya Ilmiah | Admin PSGA</title>

    <link rel="stylesheet" href="{{ asset('css/admin-karya-ilmiah.css') }}">
</head>
<body>
<main class="admin-main">

    <header class="page-header">
        <div>
            <p class="eyebrow">PSGA · UIN JAKARTA</p>
            <h1>Kelola Karya Ilmiah</h1>
            <p>Tambah, cari, edit, dan hapus data publikasi.</p>
            <a class="btn btn-secondary" href="{{ route('admin.dashboard') }}" style="margin-top: 14px;">← Kembali ke Dashboard</a>
        </div>

        <div>
            <a class="btn btn-primary" href="{{ route('admin.karya-ilmiah.create') }}">
                + Tambah Karya
            </a>
        </div>
    </header>

    {{-- Notifikasi berhasil --}}
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Notifikasi validasi --}}
    @if($errors->any())
        <div class="alert-error">
            Periksa kembali data yang dikirim. Ada isian yang belum valid.
        </div>
    @endif

    <section class="table-panel">

        {{-- Pencarian dan filter --}}
        <form method="GET"
              action="{{ route('admin.karya-ilmiah.index') }}"
              class="search-form">

            <input
                type="search"
                name="q"
                value="{{ request('q') }}"
                placeholder="Cari judul, penulis, jenis, atau deskripsi..."
            >

            <select name="jenis_publikasi" aria-label="Filter jenis publikasi">
                <option value="">Semua jenis</option>
                @foreach($jenisPublikasi as $jenis)
                    <option value="{{ $jenis }}" @selected(request('jenis_publikasi') === $jenis)>{{ $jenis }}</option>
                @endforeach
            </select>

            <select name="tahun_publikasi" aria-label="Filter tahun publikasi">
                <option value="">Semua tahun</option>
                @foreach($tahunPublikasi as $tahun)
                    <option value="{{ $tahun }}" @selected((string) request('tahun_publikasi') === (string) $tahun)>{{ $tahun }}</option>
                @endforeach
            </select>

            <button class="btn btn-secondary" type="submit">
                Terapkan
            </button>

            @if(request()->filled('q') || request()->filled('jenis_publikasi') || request()->filled('tahun_publikasi'))
                <a class="reset-link"
                   href="{{ route('admin.karya-ilmiah.index') }}">
                    Reset filter
                </a>
            @endif
        </form>

        <div class="export-actions">
            <a class="btn-export btn-export-excel"
               href="{{ route('admin.karya-ilmiah.export.excel', request()->only('q', 'jenis_publikasi', 'tahun_publikasi')) }}">
                Export Excel
            </a>
            <a class="btn-export btn-export-pdf"
               href="{{ route('admin.karya-ilmiah.export.pdf', request()->only('q', 'jenis_publikasi', 'tahun_publikasi')) }}">
                Export PDF
            </a>
        </div>

        {{-- Tabel karya ilmiah --}}
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Karya</th>
                        <th>Penulis</th>
                        <th>Jenis Publikasi</th>
                        <th>Tahun</th>
                        <th>Link</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($karyaIlmiah as $item)
                    <tr>
                        {{-- Nomor urut dimulai dari 1 --}}
                        <td>
                            {{ $karyaIlmiah->firstItem() + $loop->index }}
                        </td>

                        {{-- Judul dan deskripsi --}}
                        <td class="title-cell">
                            <strong>{{ $item->nama_karya }}</strong>
                            <small>
                                {{ \Illuminate\Support\Str::limit($item->deskripsi ?? '', 75) }}
                            </small>
                        </td>

                        {{-- Penulis --}}
                        <td>{{ $item->nama_penulis }}</td>

                        {{-- Jenis publikasi --}}
                        <td>
                            <span class="type-badge">
                                {{ $item->jenis_publikasi }}
                            </span>
                        </td>

                        {{-- Tahun publikasi --}}
                        <td>{{ $item->tahun_publikasi ?? '-' }}</td>

                        {{-- Link karya --}}
                        <td>
                            @if($item->link)
                                <a href="{{ $item->link }}"
                                   target="_blank"
                                   rel="noopener noreferrer">
                                    Buka link
                                </a>
                            @else
                                <span class="muted">—</span>
                            @endif
                        </td>

                        {{-- Tombol aksi --}}
                        <td>
                            <div class="action-group">

                                <a class="btn-edit"
                                   href="{{ route('admin.karya-ilmiah.edit', $item) }}">
                                    Edit
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('admin.karya-ilmiah.destroy', $item) }}"
                                    onsubmit="return confirm('Yakin ingin menghapus karya ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn-delete" type="submit">
                                        Hapus
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="7" class="empty-cell">
                            {{ request('q')
                                ? 'Karya ilmiah tidak ditemukan.'
                                : 'Belum ada data karya ilmiah.' }}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="pagination">
            {{ $karyaIlmiah->links('pagination.karya-ilmiah') }}
        </div>

    </section>

</main>
</body>
</html>

