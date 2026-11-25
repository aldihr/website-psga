@php
    $editing = isset($gender);
    $maleValue = old('male_count', $editing ? $gender->male_count : '');
    $femaleValue = old('female_count', $editing ? $gender->female_count : '');
    $totalValue = (int) $maleValue + (int) $femaleValue;
    $publishedValue = (string) old('is_published', $editing ? (int) $gender->is_published : '0');
    $sortOrderValue = old('sort_order', $editing ? ($gender->sort_order ?? 0) : $nextOrder);
    $needsParent = $editing
        ? !empty($gender->parent_id)
        : $parents->contains(fn ($item) => empty($item->parent_id));
@endphp

<section class="form-section" aria-labelledby="category-section-title">
    <div class="form-section-header">
        <span class="form-step">01</span>
        <div>
            <h2 id="category-section-title">Tentukan posisi data</h2>
            <p>Susun data dari yang paling umum ke yang lebih spesifik.</p>
        </div>
    </div>

    <div class="hierarchy-guide" aria-label="Contoh susunan data">
        <span class="hierarchy-guide-title">Contoh susunan</span>
        <div class="hierarchy-guide-flow">
            <span>Universitas</span><b aria-hidden="true">→</b><span>Fakultas</span><b aria-hidden="true">→</b><span>Mahasiswa</span>
        </div>
        <p>Untuk menambah Fakultas, pilih Universitas. Untuk menambah data Mahasiswa, pilih Fakultas.</p>
    </div>

    <div class="form-grid">
        @if($needsParent)
            <div class="form-field">
                <label for="name">Nama data <span class="required-mark">*</span></label>
                <input id="name" name="name" type="text" maxlength="255"
                       value="{{ old('name', $gender->name ?? '') }}"
                       placeholder="Contoh: Fakultas Ilmu Sosial atau Mahasiswa" required autofocus>
                <small>Nama ini akan menjadi pilihan untuk tingkat berikutnya. Hindari nama persis “Universitas”; contoh nama yang boleh: Universitas_Dosen.</small>
                @error('name')<small class="field-error">{{ $message }}</small>@enderror
            </div>

            <div class="form-field">
                <label for="parent_id">Data ini berada di bawah <span class="required-mark">*</span></label>
                <select id="parent_id" name="parent_id" required>
                    <option value="">Pilih data yang menaungi data ini</option>
                    @foreach($parents as $option)
                        <option value="{{ $option->id }}"
                                data-option-name="{{ $option->name }}"
                                data-male-available="{{ $option->available_male_count }}"
                                data-female-available="{{ $option->available_female_count }}"
                                @selected((string) old('parent_id', $parentId) === (string) $option->id)>
                            {{ $option->name }} — {{ $option->parent_name ? 'bagian dari ' . $option->parent_name : 'data utama Universitas' }}
                        </option>
                    @endforeach
                </select>
                <small class="field-hint" id="parent-limit-hint" aria-live="polite">Pilih satu data di atasnya. Contoh: pilih Universitas untuk Fakultas, atau Fakultas untuk Mahasiswa.</small>
                @error('parent_id')<small class="field-error">{{ $message }}</small>@enderror
            </div>
        @else
            <div class="university-fixed form-field">
                <span class="field-label">Jenis data</span>
                <input type="text" value="Universitas" readonly aria-readonly="true">
                <input type="hidden" name="name" value="{{ $gender->name ?? 'Universitas' }}">
                <small>Data Universitas sudah ditetapkan sebagai tingkat pertama dan tidak perlu diberi nama manual.</small>
                @error('name')<small class="field-error">{{ $message }}</small>@enderror
            </div>
            <div class="parent-root-note">
                <strong>{{ $editing ? 'Ini data utama Universitas' : 'Langkah pertama: tambahkan Universitas' }}</strong>
                <span>{{ $editing ? 'Data utama tidak berada di bawah data lain.' : 'Setelah tersimpan, Fakultas dapat ditambahkan di bawah Universitas, lalu Mahasiswa di bawah Fakultas.' }}</span>
            </div>
        @endif
    </div>
</section>

<section class="form-section" aria-labelledby="counts-section-title">
    <div class="form-section-header">
        <span class="form-step">02</span>
        <div>
            <h2 id="counts-section-title">Jumlah gender</h2>
            <p>Total dihitung otomatis dan menjadi batas bagi kategori di bawahnya.</p>
        </div>
    </div>

    <div class="form-grid form-grid-two">
        <div class="form-field">
            <label for="male_count">Laki-laki <span class="required-mark">*</span></label>
            <div class="number-input-wrap">
                <input id="male_count" name="male_count" type="number" min="0" step="1" value="{{ $maleValue }}" placeholder="0" required>
                <span>orang</span>
            </div>
            @error('male_count')<small class="field-error">{{ $message }}</small>@enderror
        </div>

        <div class="form-field">
            <label for="female_count">Perempuan <span class="required-mark">*</span></label>
            <div class="number-input-wrap">
                <input id="female_count" name="female_count" type="number" min="0" step="1" value="{{ $femaleValue }}" placeholder="0" required>
                <span>orang</span>
            </div>
            @error('female_count')<small class="field-error">{{ $message }}</small>@enderror
        </div>

        <div class="total-preview form-field-wide">
            <span>Total: </span>
            <strong id="total-preview">{{ number_format($totalValue, 0, ',', '.') }} orang</strong>
        </div>
    </div>
</section>

<section class="form-section" aria-labelledby="visibility-section-title">
    <div class="form-section-header">
        <span class="form-step">03</span>
        <div>
            <h2 id="visibility-section-title">Tampilan halaman</h2>
            <p>Data bisa disimpan sebagai Draft dulu atau langsung dipublikasikan.</p>
        </div>
    </div>

    <div class="form-field">
        <span class="field-label">Status publikasi</span>
        <div class="publish-options" role="radiogroup" aria-label="Status publikasi">
            <label class="publish-option">
                <input type="radio" name="is_published" value="0" @checked($publishedValue === '0') required>
                <span><strong>Draft</strong><small>Belum tampil di halaman Gender.</small></span>
            </label>
            <label class="publish-option">
                <input type="radio" name="is_published" value="1" @checked($publishedValue === '1') required>
                <span><strong>Publish</strong><small>Tampil di halaman Gender.</small></span>
            </label>
        </div>
        @error('is_published')<small class="field-error">{{ $message }}</small>@enderror
    </div>
</section>

<details class="form-advanced" @if($errors->has('sort_order') || $errors->has('description')) open @endif>
    <summary>Pengaturan tambahan <span>Opsional</span></summary>
    <div class="form-grid form-grid-two form-advanced-content">
        <div class="form-field">
            <label for="sort_order">Urutan tampil</label>
            <input id="sort_order" name="sort_order" type="number" min="0" step="1" value="{{ $sortOrderValue }}">
            <small>Angka lebih kecil tampil lebih dulu.</small>
            @error('sort_order')<small class="field-error">{{ $message }}</small>@enderror
        </div>

        <div class="form-field">
            <label for="description">Keterangan</label>
            <textarea id="description" name="description" rows="3" maxlength="2000"
                      placeholder="Tambahkan keterangan jika diperlukan...">{{ old('description', $gender->description ?? '') }}</textarea>
            <small>Informasi tambahan untuk membantu menjelaskan kategori.</small>
            @error('description')<small class="field-error">{{ $message }}</small>@enderror
        </div>
    </div>
</details>

<script>
(function () {
    const male = document.getElementById('male_count');
    const female = document.getElementById('female_count');
    const total = document.getElementById('total-preview');
    const parentSelect = document.getElementById('parent_id');
    const parentLimitHint = document.getElementById('parent-limit-hint');
    const format = new Intl.NumberFormat('id-ID');

    function updateTotal() {
        total.textContent = format.format(Math.max(0, Number(male.value) || 0) + Math.max(0, Number(female.value) || 0)) + ' orang';
    }

    function updateParentLimit() {
        if (!parentSelect || !parentLimitHint) return;
        const option = parentSelect.options[parentSelect.selectedIndex];
        if (!option.value || !option.dataset.optionName) {
            parentLimitHint.textContent = 'Pilih data satu tingkat di atas data baru. Contoh: Fakultas berada di bawah Universitas.';
            return;
        }
        const maleAvailable = format.format(Number(option.dataset.maleAvailable || 0));
        const femaleAvailable = format.format(Number(option.dataset.femaleAvailable || 0));
        parentLimitHint.textContent = `Data baru akan berada di bawah ${option.dataset.optionName}. Sisa kuotanya: ${maleAvailable} laki-laki dan ${femaleAvailable} perempuan.`;
    }

    [male, female].forEach((input) => {
        input.addEventListener('focus', () => {
            if (input.value === '0') {
                input.value = '';
                updateTotal();
            }
        });
        input.addEventListener('blur', () => {
            if (input.value === '') input.value = '0';
            updateTotal();
        });
        input.addEventListener('input', updateTotal);
    });
    parentSelect?.addEventListener('change', updateParentLimit);
    updateTotal();
    updateParentLimit();
})();
</script>
