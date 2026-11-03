<!DOCTYPE html>
<html>
<head>
    <title>Form Pengaduan PSGA</title>

    <style>

        .alert-success {
    background-color: #e8f7ee;
    border: 1px solid #28a745;
    color: #155724;
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
}

.alert-success strong {
    display: block;
    font-size: 18px;
    margin-bottom: 5px;
}

.alert-success p {
    margin: 0;
    font-size: 14px;
}

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 40px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h1 {
            color: #6a1b9a;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            height: 120px;
            resize: vertical;
        }

        button {
            margin-top: 20px;
            background: #6a1b9a;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #4a126b;
        }

        .error {
            color: red;
            margin-top: 15px;
        }
        
        .btn-home {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 18px;
    background-color: #6c757d;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
}

.btn-home:hover {
    background-color: #5a6268;
    color: white;
}
    </style>
</head>

<body>

<div class="container">

    <h1>Form Pengaduan PSGA</h1>
<div class="alert alert-info mt-3 mb-4" role="note">
    <div class="d-flex align-items-start">
        <div>
<p style="margin-top: -10px; margin-bottom: 15px;">
    Privasi dan kerahasiaan laporan Anda kami jaga.
</p>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert-success">
        <strong>✓ Pengaduan Berhasil!</strong>
        <p>{{ session('success') }}</p>
    </div>
@endif

    @if ($errors->any())
        <div class="error">
            <strong>Terjadi kesalahan:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pengaduan.store') }}" method="POST">

        @csrf

        <label>Nama Pelapor</label>
        <input type="text" name="nama_pelapor" value="{{ old('nama_pelapor') }}" required>
        
<div class="mb-3">
    <label for="terlapor" class="form-label">Nama Terlapor</label>
    <input
        type="text"
        name="terlapor"
        id="terlapor"
        class="form-control"
        value="{{ old('terlapor') }}"
        placeholder="Masukkan nama pihak yang dilaporkan"
        maxlength="255"
        required
    >
</div>

        <label>Email</label>
        <input type="email" name="email_pelapor" value="{{ old('email_pelapor') }}" required>

        <label>Nomor WhatsApp Pelapor yang Aktif</label>
        <input type="text" name="no_hp" value="{{ old('no_hp') }}" required>
       

<label>Fakultas</label>
<select name="fakultas" required>
    <option value="">-- Pilih Fakultas --</option>
    <option value="Fakultas Ilmu Dakwah dan Ilmu Komunikasi">Fakultas Ilmu Dakwah dan Ilmu Komunikasi</option>
    <option value="Fakultas Syariah dan Hukum">Fakultas Syariah dan Hukum</option>
    <option value="Fakultas Tarbiyah dan Keguruan">Fakultas Tarbiyah dan Keguruan</option>
    <option value="Fakultas Ushuluddin">Fakultas Ushuluddin</option>
    <option value="Fakultas Ekonomi dan Bisnis">Fakultas Ekonomi dan Bisnis</option>
    <option value="Fakultas Sains dan Teknologi">Fakultas Sains dan Teknologi</option>
    <option value="Fakultas Kedokteran">Fakultas Kedokteran</option>
    <option value="Fakultas Ilmu Kesehatan">Fakultas Ilmu Kesehatan</option>
    <option value="Fakultas Dirasat Islamiyah">Fakultas Dirasat Islamiyah</option>
    <option value="Fakultas Ilmu Sosial dan Politik">Fakultas Ilmu Sosial dan Politik</option>
    <option value="Pascasarjana">Pascasarjana</option>
    <option value="Lainnya">Lainnya</option>
</select>

        <label>Kategori Pengaduan</label>
        <select name="kategori_pengaduan" required>
    <option value="Kekerasan" {{ old('kategori_pengaduan') == 'Kekerasan' ? 'selected' : '' }}>Kekerasan</option>
    <option value="Diskriminasi" {{ old('kategori_pengaduan') == 'Diskriminasi' ? 'selected' : '' }}>Diskriminasi</option>
    <option value="Bullying" {{ old('kategori_pengaduan') == 'Bullying' ? 'selected' : '' }}>Bullying</option>
    <option value="Pelecehan Seksual" {{ old('kategori_pengaduan') == 'Pelecehan Seksual' ? 'selected' : '' }}>Pelecehan Seksual</option>
    <option value="Lainnya" {{ old('kategori_pengaduan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
</select>

        <label>Isi Pengaduan</label>
        <textarea name="isi_pengaduan" required>{{ old('isi_pengaduan') }}</textarea>

        <button type="submit">Kirim Pengaduan</button>

        <a href="{{ url('/') }}" class="btn-home">
    ← Kembali ke Beranda
</a>

    </form>

</div>

</body>
</html>