<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karya Ilmiah | PSGA UIN Jakarta</title>
    <link rel="stylesheet" href="{{ asset('css/karya-ilmiah.css') }}"> 
<body>
<main class="page-container">
    <section class="page-intro">
        <div class="intro-copy">
            <p class="eyebrow">PUSAT STUDI GENDER DAN ANAK</p>
            <h1>Karya Ilmiah</h1>
            <p class="intro-description">Jelajahi publikasi, penelitian, dan pemikiran akademik seputar gender, anak, dan pemberdayaan masyarakat.</p>
        </div>
        <div class="intro-mark">
            <img src="{{ asset('images/PSGA.jpg') }}" alt="PSGA">
        </div>
    </section>

    <form class="search-form" method="GET" action="{{ route('karya-ilmiah.index') }}">
        <label class="search-box">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"></circle><path d="m16 16 4.2 4.2"></path></svg>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari judul karya, nama penulis, atau deskripsi..." aria-label="Cari karya ilmiah">
        </label>
        <select class="filter-select" name="jenis_publikasi" aria-label="Filter jenis publikasi">
            <option value="">Semua jenis</option>
            @foreach($jenisPublikasi as $jenis)
                <option value="{{ $jenis }}" @selected(request('jenis_publikasi') === $jenis)>{{ $jenis }}</option>
            @endforeach
        </select>
        <select class="filter-select" name="tahun_publikasi" aria-label="Filter tahun publikasi">
            <option value="">Semua tahun</option>
            @foreach($tahunPublikasi as $tahun)
                <option value="{{ $tahun }}" @selected((string) request('tahun_publikasi') === (string) $tahun)>{{ $tahun }}</option>
            @endforeach
        </select>
        <button type="submit" class="search-button">Terapkan</button>
        @if(request()->filled('q') || request()->filled('jenis_publikasi') || request()->filled('tahun_publikasi'))
            <a class="reset-search" href="{{ route('karya-ilmiah.index') }}">Reset filter</a>
        @endif
    </form>

    <section class="publication-section" aria-labelledby="publication-heading">
        <div class="section-heading">
            <div>
                <h2 id="publication-heading">Daftar Publikasi</h2>
                <p>Kumpulan karya ilmiah dan referensi akademik PSGA.</p>
            </div>
            @if(isset($karyaIlmiah)) <span class="result-count">{{ $karyaIlmiah->total() }} publikasi</span> @endif
        </div>

        @if(isset($karyaIlmiah) && $karyaIlmiah->count())
            <div class="artikel-grid">
                @foreach($karyaIlmiah as $item)
                    <article class="artikel-card">
                        <div class="card-topline">
                            <span class="article-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M6 3.75h8l4 4V20a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V4.75a1 1 0 0 1 1-1Z"/><path d="M14 4v4h4M8 12h8M8 15.5h8M8 19h5"/></svg>
                            </span>
                        </div>
                        <p class="article-category">{{ $item->jenis_publikasi }}</p>
                        <h3>{{ $item->nama_karya }}</h3>
                        <p class="article-author">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.25"/><path d="M5.5 20a6.5 6.5 0 0 1 13 0"/></svg>
                            {{ $item->nama_penulis }}
                        </p>
                        <p class="article-description">{{ $item->deskripsi ?: 'Belum ada deskripsi untuk karya ini.' }}</p>
                        <div class="card-footer">
                            <span>
                                Tahun Publikasi:
                                {{ $item->tahun_publikasi ?? '-' }}
                            </span>
                            @if($item->link)
                                <a href="{{ $item->link }}" target="_blank" rel="noopener noreferrer">Baca Selengkapnya
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 4h6v6M20 4l-9 9"/><path d="M18 13v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h5"/></svg>
                                </a>
                            @else
                                <span class="no-link">Link belum tersedia</span>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="pagination-wrap">{{ $karyaIlmiah->withQueryString()->links('pagination.karya-ilmiah') }}</div>
        @else
            <div class="empty-state">
                <span class="empty-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M6 3.75h8l4 4V20a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V4.75a1 1 0 0 1 1-1Z"/><path d="M14 4v4h4M8 13h8M8 16h5"/></svg></span>
                @if(request('q'))
                    <h3>Karya tidak ditemukan</h3><p>Coba kata kunci lain atau hapus pencarian untuk melihat semua publikasi.</p>
                @else
                    <h3>Belum ada karya ilmiah</h3><p>Publikasi akan ditampilkan di sini setelah data ditambahkan ke database.</p>
                @endif
            </div>
        @endif
    </section>
    <footer class="page-footer"><span>PSGA · UIN Syarif Hidayatullah Jakarta</span><span>Pusat Studi Gender dan Anak</span></footer>
</main>
</body>
</html>
