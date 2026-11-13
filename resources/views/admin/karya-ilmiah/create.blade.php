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
        <h1>Tambah Karya Ilmiah</h1>
        <p>Lengkapi informasi publikasi di bawah ini.</p>

        <form method="POST" action="{{ route('admin.karya-ilmiah.store') }}">
            @csrf
            @include('admin.karya-ilmiah._form')
        </form>
    </section>
</main>
</body>
</html>
