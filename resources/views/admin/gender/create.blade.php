<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Gender | Admin PSGA</title>
    <link rel="stylesheet" href="{{ asset('css/admin-gender.css') }}">
</head>
<body>
<main class="page form-page">
    <header class="page-header">
        <div class="header-copy">
            <span class="eyebrow">PSGA · MANAJEMEN DATA</span>
            <h1>Tambah Data Gender</h1>
            <p>Isi informasi utama, jumlah, dan status data dalam satu alur.</p>
        </div>
        <a href="{{ route('admin.gender.index', $parentId ? ['node_id' => $parentId] : []) }}" class="btn btn-back header-action">← Kembali ke data gender</a>
    </header>

    @if($errors->any())
        <div class="alert alert-error" role="alert"><strong>Beberapa isian perlu diperbaiki.</strong> Lihat pesan di bawah kolom yang ditandai.</div>
    @endif

    <form action="{{ route('admin.gender.store') }}" method="POST" class="data-form">
        @csrf
        @include('admin.gender.partials.form-fields', ['gender' => null])

        <div class="form-footer">
            <a href="{{ route('admin.gender.index', $parentId ? ['node_id' => $parentId] : []) }}" class="btn btn-back">Batal</a>
            <button type="submit" class="btn btn-add">Simpan data <span aria-hidden="true">→</span></button>
        </div>
    </form>
</main>
</body>
</html>
