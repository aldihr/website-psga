<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Proses Pengaduan PSGA</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        h1 {
            color: #6a1b9a;
        }

        .info {
            background-color: #f3e5f5;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        select,
        textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            height: 150px;
        }

        .btn {
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #6a1b9a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #4a148c;
        }

        .back {
            display: inline-block;
            margin-top: 15px;
            color: #6a1b9a;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Proses Pengaduan</h1>

    <div class="info">
        <strong>Nama Pelapor:</strong>
        {{ $pengaduan->nama_pelapor }}
        <br><br>

        <strong>Judul Pengaduan:</strong>
        {{ $pengaduan->judul_pengaduan }}
        <br><br>

        <strong>Isi Pengaduan:</strong>
        {{ $pengaduan->isi_pengaduan }}
    </div>

    <form action="{{ route('pengaduan.update', $pengaduan->id) }}" method="POST">

        @csrf
        @method('PUT')

        <label>Status Pengaduan</label>

       <select name="status" required>

    <option value="Pengajuan"
        {{ $pengaduan->status == 'Pengajuan Pengaduan' ? 'selected' : '' }}>
        Pengajuan
    </option>

    <option value="Verifikasi"
        {{ $pengaduan->status == 'Verifikasi' ? 'selected' : '' }}>
        Verifikasi
    </option>

    <option value="Pendalaman"
        {{ $pengaduan->status == 'Pendalaman' ? 'selected' : '' }}>
        Pendalaman
    </option>

    <option value="Tindak Lanjut"
        {{ $pengaduan->status == 'Tindak Lanjut' ? 'selected' : '' }}>
        Tindak Lanjut
    </option>

    <option value="Selesai"
        {{ $pengaduan->status == 'Selesai' ? 'selected' : '' }}>
        Selesai
    </option>

</select>


        <label>Tanggapan Admin</label>

        <textarea name="tanggapan"
                  placeholder="Tuliskan tanggapan untuk pelapor...">{{ $pengaduan->tanggapan }}</textarea>

        <button type="submit" class="btn">
            Simpan Perubahan
        </button>

    </form>

    <a href="{{ route('pengaduan.admin') }}" class="back">
        ← Kembali ke Data Pengaduan
    </a>

</div>

</body>
</html>