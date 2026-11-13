@php
    $editing = isset($karyaIlmiah);
@endphp

<div class="form-grid">
    <label>
        Judul Karya *
        <input name="nama_karya" required maxlength="255"
               value="{{ old('nama_karya', $karyaIlmiah->nama_karya ?? '') }}">
        @error('nama_karya') <small class="field-error">{{ $message }}</small> @enderror
    </label>

    <label>
        Nama Penulis *
        <input name="nama_penulis" required maxlength="255"
               value="{{ old('nama_penulis', $karyaIlmiah->nama_penulis ?? '') }}">
        @error('nama_penulis') <small class="field-error">{{ $message }}</small> @enderror
    </label>

    <label>
        Jenis Publikasi *
        <div class="form-group">

            @php
                $jenisPublikasi = [
                    'Artikel Jurnal',
                    'Artikel Jurnal Nasional',
                    'Artikel Jurnal Internasional',
                    'Prosiding',
                    'Makalah Konferensi',
                    'Buku',
                    'Laporan Penelitian',
                    'Laporan Pengabdian kepada Masyarakat',
                    'Skripsi',
                    'Tesis',
                    'Disertasi',
                    'Artikel Ilmiah Populer',
                    'Working Paper',
                    'Modul',
                    'Naskah Akademik',
                    'Laporan Kajian',
                    'Publikasi Hasil Penelitian',
                    'Lainnya',
                ];

                $jenisTerpilih = old(
                    'jenis_publikasi',
                    $karyaIlmiah->jenis_publikasi ?? ''
                );
            @endphp

            <select id="jenis_publikasi" name="jenis_publikasi" required>
                <option value="">-- Pilih Jenis Publikasi --</option>

                @foreach ($jenisPublikasi as $jenis)
                    <option
                        value="{{ $jenis }}"
                        {{ $jenisTerpilih === $jenis ? 'selected' : '' }}
                    >
                        {{ $jenis }}
                    </option>
                @endforeach
            </select>

            @error('jenis_publikasi')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>
    </label>

    <label>
        Tahun Publikasi *
        <div class="form-group">
            <select id="tahun_publikasi" name="tahun_publikasi" required>
                <option value="">Pilih Tahun</option>

                @for ($tahun = (int) date('Y'); $tahun >= 1901; $tahun--)
                <option
                    value="{{ $tahun }}"
                    {{ (string) old('tahun_publikasi', $karyaIlmiah->tahun_publikasi ?? '') === (string) $tahun ? 'selected' : '' }}
                >
                {{ $tahun }}
                </option>
            @endfor
            </select>

            @error('tahun_publikasi')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>
    </label>

    <label class="full-width">
        Deskripsi
        <textarea name="deskripsi" rows="5">{{ old('deskripsi', $karyaIlmiah->deskripsi ?? '') }}</textarea>
        @error('deskripsi') <small class="field-error">{{ $message }}</small> @enderror
    </label>

    <label class="full-width">
        Link Publikasi
        <input type="url" name="link" maxlength="2048"
               placeholder="https://..."
               value="{{ old('link', $karyaIlmiah->link ?? '') }}">
        @error('link') <small class="field-error">{{ $message }}</small> @enderror
    </label>
</div>

<div class="form-actions">
    <a class="btn btn-secondary" href="{{ route('admin.karya-ilmiah.index') }}">Batal</a>
    <button class="btn btn-primary" type="submit">
        {{ $editing ? 'Simpan Perubahan' : 'Simpan Karya' }}
    </button>
</div>
