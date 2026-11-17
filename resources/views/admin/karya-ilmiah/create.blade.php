<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Karya | Admin PSGA</title>
    <link rel="stylesheet" href="{{ asset('css/admin-karya-ilmiah.css') }}">
</head>
<body>
<main class="admin-main">
    <a class="back-link" href="{{ route('admin.karya-ilmiah.index') }}">← Kembali ke daftar</a>
    <section class="form-panel">
        <div class="form-heading">
            <div>
                <h1>Tambah Karya Ilmiah</h1>
                <p>Lengkapi informasi publikasi di bawah ini.</p>
            </div>
            <div class="database-number" aria-label="Nomor database akan dibuat setelah disimpan">
                <span>No. Database</span>
                <strong>Otomatis</strong>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.karya-ilmiah.store') }}">
            @csrf
            @include('admin.karya-ilmiah._form')
        </form>
    </section>
</main>
</body>
</html>
