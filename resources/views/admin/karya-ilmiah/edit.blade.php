<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Karya | Admin PSGA</title>
    <link rel="stylesheet" href="{{ asset('css/admin-karya-ilmiah.css') }}">
</head>
<body>
<main class="admin-main">
    <a class="back-link" href="{{ route('admin.karya-ilmiah.index') }}">← Kembali ke daftar</a>
    <section class="form-panel">
        <div class="form-heading">
            <div>
                <h1>Edit Karya Ilmiah</h1>
                <p>Perbarui informasi publikasi yang dipilih.</p>
            </div>
            <div class="database-number" aria-label="Nomor database {{ $karyaIlmiah->id }}">
                <span>No. Database</span>
                <strong>#{{ $karyaIlmiah->id }}</strong>
            </div>
        </div>

        <form method="POST"
              action="{{ route('admin.karya-ilmiah.update', $karyaIlmiah) }}">
            @csrf
            @method('PUT')
            @include('admin.karya-ilmiah._form')
        </form>
    </section>
</main>
</body>
</html>
