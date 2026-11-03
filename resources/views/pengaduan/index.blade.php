<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengaduan PSGA</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #6a1b9a;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        textarea {
            min-height: 150px;
            resize: vertical;
        }

        button {
            width: 100%;
            padding: 13px;
            background-color: #6a1b9a;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #4a126b;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Form Pengaduan PSGA</h1>

    <div class="subtitle">
        Pusat Studi Gender dan Anak UIN Syarif Hidayatullah Jakarta
    </div>

    <form action="{{ route('pengaduan.store') }}" method="POST">

        @csrf

        <label for="nama_pelapor">Nama Pelapor</label>
        <input
            type="text"
            id="nama_pelapor"
            name="nama_pelapor"
            placeholder="Masukkan nama lengkap"
            required
        >

        <label for="email_pelapor">Email Pelapor</label>
        <input
            type="email"
            id="email_pelapor"
            name="email_pelapor"
            placeholder="Masukkan email"
            required
        >

        <label for="no_hp">Nomor HP</label>
        <input
            type="text"
            id="no_hp"
            name="no_hp"
            placeholder="Masukkan nomor HP"
            required
        >

        <label for="judul_pengaduan">Judul Pengaduan</label>
        <input
            type="text"
            id="judul_pengaduan"
            name="judul_pengaduan"
            placeholder="Masukkan judul pengaduan"
            required
        >

        <label for="isi_pengaduan">Isi Pengaduan</label>
        <textarea
            id="isi_pengaduan"
            name="isi_pengaduan"
            placeholder="Tuliskan pengaduan Anda secara lengkap"
            required
        ></textarea>

        <button type="submit">
            Kirim Pengaduan
        </button>

    </form>

</div>

</body>
</html>