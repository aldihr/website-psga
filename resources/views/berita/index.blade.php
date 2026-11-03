
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Berita - PSGA</title>
    <style>

        
.btn-kembali {
    display: inline-block;
    background: #6c757d;
    color: white;
    padding: 10px 16px;
    border-radius: 7px;
    text-decoration: none;
    font-size: 15px;
    margin-right: 10px;
    margin-bottom: 15px;
    transition: 0.2s;
}

.btn-kembali:hover {
    background: #565e64;
    color: white;
}

        body {
            font-family: Arial, sans-serif;
            background: #f7f5fa;
            margin: 0;
            padding: 30px;
            color: #333;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
        }

        h1 {
            color: #A10F79;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            background: #A10F79;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #A10F79;
            color: white;
        }

        .status {
            font-weight: bold;
        }

        .aksi-berita {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
}

.aksi-berita form {
    margin: 0;
    width: 90px;
}

.btn-aksi {
    display: block;
    width: 90px;
    min-height: 34px;
    padding: 7px 8px;
    box-sizing: border-box;
    border: none;
    border-radius: 5px;
    color: white;
    font-size: 13px;
    text-align: center;
    cursor: pointer;
}

.btn-arsip {
    background-color: #6c757d;
}

.btn-arsip:hover {
    background-color: #565e64;
}

.btn-hapus {
    background-color: #dc3545;
}

.btn-hapus:hover {
    background-color: #bb2d3b;
}


.tombol-aksi {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: center;
    margin-bottom: 20px;
}

.btn-arsip {
    display: inline-block;
    padding: 10px 16px;
    background: #6c757d;
    color: white;
    text-decoration: none;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
}

.btn-arsip:hover {
    background: #565e64;
}


    </style>
</head>
<body>
    <div class="container">
        <h1>Kelola Berita PSGA</h1>
        <p>Kelola berita dan informasi yang ditampilkan di website PSGA.</p>

<a href="{{ url('/admin/dashboard') }}" class="btn-kembali">
    ← Kembali ke Dashboard
</a>

        
<div class="tombol-aksi">
    <a href="{{ route('berita.create') }}" class="btn">
        + Tambah Berita
    </a>

    <a href="{{ route('berita.arsip') }}" class="btn btn-arsip">
        📁 Lihat Arsip
    </a>
</div>


        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif

        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Judul Berita</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Tanggal Terbit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($berita as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->judul }}</td>
                        <td>{{ $item->kategori ?? '-' }}</td>
                        <td class="status">{{ ucfirst($item->status) }}</td>
                        <td>
                            {{ $item->tanggal_terbit?->format('d-m-Y') ?? '-' }}
                        </td>
<td>
    <div class="aksi-berita">
        @if ($item->status === 'terbit')
            <form action="{{ route('berita.arsipkan', $item->id) }}"
                  method="POST"
                  onsubmit="return confirm('Arsipkan berita ini?')">
                @csrf
                @method('PATCH')

                <button type="submit" class="btn-aksi btn-arsip">
                    Arsipkan
                </button>
            </form>
        @endif

        <form action="{{ route('berita.hapus', $item->id) }}"
              method="POST"
              onsubmit="return confirm('Yakin ingin menghapus berita ini secara permanen?')">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn-aksi btn-hapus">
                Hapus
            </button>
        </form>
    </div>
</td>                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Belum ada berita.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $berita->links() }}
    </div>
</body>
</html>
