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

                $jenisTerpilih = $karyaIlmiah->jenis_publikasi ?? '';
                $jenisPresetAwal = in_array($jenisTerpilih, $jenisPublikasi, true)
                    ? $jenisTerpilih
                    : ($jenisTerpilih !== '' ? 'Lainnya' : '');
                $jenisPreset = old('jenis_publikasi_pilihan', $jenisPresetAwal);
                $jenisLainnyaAwal = $jenisPresetAwal === 'Lainnya' ? $jenisTerpilih : '';
                $jenisLainnya = old('jenis_publikasi_lainnya', $jenisLainnyaAwal);
            @endphp

            <select id="jenis_publikasi_pilihan" name="jenis_publikasi_pilihan" required>
                <option value="">-- Pilih Jenis Publikasi --</option>

                @foreach ($jenisPublikasi as $jenis)
                    <option
                        value="{{ $jenis }}"
                        {{ $jenisPreset === $jenis ? 'selected' : '' }}
                    >
                        {{ $jenis }}
                    </option>
                @endforeach
            </select>

            <input
                id="jenis_publikasi_lainnya"
                name="jenis_publikasi_lainnya"
                type="text"
                maxlength="100"
                placeholder="Ketik jenis publikasi"
                value="{{ $jenisLainnya }}"
                {{ $jenisPreset === 'Lainnya' ? '' : 'hidden' }}
            >

            @error('jenis_publikasi')
                <small class="error-message">{{ $message }}</small>
            @enderror
            @error('jenis_publikasi_lainnya')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>
    </label>

    <label>
        Tahun Publikasi *
        <div class="form-group year-picker" id="year_picker">
            @php
                $tahunTerpilih = (string) old('tahun_publikasi', $karyaIlmiah->tahun_publikasi ?? '');
            @endphp
            <input type="hidden" id="tahun_publikasi" name="tahun_publikasi" value="{{ $tahunTerpilih }}">
            <button
                class="year-picker-trigger"
                id="year_picker_trigger"
                type="button"
                aria-haspopup="listbox"
                aria-expanded="false"
                aria-controls="year_picker_options"
            >{{ $tahunTerpilih !== '' ? $tahunTerpilih : 'Pilih Tahun' }}</button>
            <div class="year-picker-options" id="year_picker_options" role="listbox" aria-label="Pilih tahun publikasi" hidden>
                @for ($tahun = now()->year; $tahun >= 1901; $tahun--)
                    <button
                        class="year-picker-option"
                        type="button"
                        role="option"
                        aria-selected="{{ $tahunTerpilih === (string) $tahun ? 'true' : 'false' }}"
                        data-year="{{ $tahun }}"
                    >{{ $tahun }}</button>
                @endfor
            </div>

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

<script>
    (() => {
        const pilihan = document.getElementById('jenis_publikasi_pilihan');
        const lainnya = document.getElementById('jenis_publikasi_lainnya');

        const perbaruiJenisPublikasi = () => {
            const pilihLainnya = pilihan.value === 'Lainnya';
            lainnya.hidden = !pilihLainnya;
            if (!pilihLainnya) lainnya.value = '';
        };

        pilihan.addEventListener('change', perbaruiJenisPublikasi);
        perbaruiJenisPublikasi();

        const yearPicker = document.getElementById('year_picker');
        const yearTrigger = document.getElementById('year_picker_trigger');
        const yearOptions = document.getElementById('year_picker_options');
        const yearInput = document.getElementById('tahun_publikasi');

        const tutupPilihanTahun = () => {
            yearOptions.hidden = true;
            yearTrigger.setAttribute('aria-expanded', 'false');
        };

        yearTrigger.addEventListener('click', () => {
            const akanDibuka = yearOptions.hidden;
            yearOptions.hidden = !akanDibuka;
            yearTrigger.setAttribute('aria-expanded', String(akanDibuka));
            if (akanDibuka && yearInput.value) {
                yearOptions.querySelector(`[data-year="${yearInput.value}"]`)?.scrollIntoView({ block: 'nearest' });
            }
        });

        yearOptions.addEventListener('click', (event) => {
            const option = event.target.closest('[data-year]');
            if (!option) return;

            yearInput.value = option.dataset.year;
            yearTrigger.textContent = option.dataset.year;
            yearOptions.querySelectorAll('[role="option"]').forEach((item) => {
                item.setAttribute('aria-selected', String(item === option));
            });
            tutupPilihanTahun();
        });

        document.addEventListener('click', (event) => {
            if (!yearPicker.contains(event.target)) tutupPilihanTahun();
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') tutupPilihanTahun();
        });
    })();
</script>

<div class="form-actions">
    <a class="btn btn-secondary" href="{{ route('admin.karya-ilmiah.index') }}">Batal</a>
    <button class="btn btn-primary" type="submit">
        {{ $editing ? 'Simpan Perubahan' : 'Simpan Karya' }}
    </button>
</div>

@php($duplicateSummary = session('karyaIlmiahDuplikat'))
<input type="hidden" id="konfirmasi_duplikat" name="konfirmasi_duplikat" value="0">
<div
    class="duplicate-modal"
    id="duplicate_modal"
    role="dialog"
    aria-modal="true"
    aria-labelledby="duplicate_modal_title"
    {{ $duplicateSummary ? '' : 'hidden' }}
>
    <div class="duplicate-modal-card">
        <h2 id="duplicate_modal_title">Publikasi serupa sudah ada</h2>
        <p>Judul dan jenis publikasi ini cocok dengan data berikut. Apakah Anda tetap ingin menyimpan?</p>

        @if($duplicateSummary)
            <dl class="duplicate-summary">
                <div><dt>Judul</dt><dd>{{ $duplicateSummary['nama_karya'] }}</dd></div>
                <div><dt>Penulis</dt><dd>{{ $duplicateSummary['nama_penulis'] }}</dd></div>
                <div><dt>Jenis publikasi</dt><dd>{{ $duplicateSummary['jenis_publikasi'] }}</dd></div>
                <div><dt>Tahun</dt><dd>{{ $duplicateSummary['tahun_publikasi'] ?? '-' }}</dd></div>
                <div><dt>Deskripsi</dt><dd>{{ $duplicateSummary['deskripsi'] ?: 'Tidak ada deskripsi.' }}</dd></div>
                <div><dt>Ditambahkan (WIB)</dt><dd>{{ $duplicateSummary['created_at_local'] ?? '-' }}</dd></div>
                <div><dt>Ditambahkan (UTC)</dt><dd>{{ $duplicateSummary['created_at_utc'] ?? '-' }}</dd></div>
            </dl>
        @endif

        <div class="duplicate-modal-actions">
            @if($duplicateSummary)
                <a class="btn btn-secondary" href="{{ route('admin.karya-ilmiah.edit', $duplicateSummary['id']) }}">Edit data yang sudah ada</a>
            @endif
            <button class="btn btn-secondary" id="duplicate_cancel" type="button">Periksa kembali</button>
            <button class="btn btn-primary" id="duplicate_confirm" type="button">Tetap simpan</button>
        </div>
    </div>
</div>

<script>
    (() => {
        const form = document.getElementById('konfirmasi_duplikat').form;
        const modal = document.getElementById('duplicate_modal');
        const cancel = document.getElementById('duplicate_cancel');
        const confirm = document.getElementById('duplicate_confirm');
        const confirmation = document.getElementById('konfirmasi_duplikat');

        const tutupDialogDuplikat = () => {
            modal.hidden = true;
            confirmation.value = '0';
        };

        cancel.addEventListener('click', tutupDialogDuplikat);
        confirm.addEventListener('click', () => {
            confirmation.value = '1';
            form.requestSubmit();
        });
        modal.addEventListener('click', (event) => {
            if (event.target === modal) tutupDialogDuplikat();
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !modal.hidden) tutupDialogDuplikat();
        });

        if (!modal.hidden) cancel.focus();
    })();
</script>
