<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Berita - PSGA UIN Jakarta</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f5fa;
            margin: 0;
            padding: 30px 16px;
            color: #333;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px #00000012;
        }

        h1 {
            color: #A10F79;
            margin-top: 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            background: #A10F79;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-restore {
            background: #198754;
        }

        .table-wrapper {
            overflow-x: auto;
            margin-top: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #A10F79;
            color: white;
            text-align: left;
            padding: 14px 12px;
        }

        td {
            padding: 14px 12px;
            border-bottom: 1px solid #ddd;
        }

        .aksi {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 110px;
        }

        .kosong {
            padding: 24px;
            text-align: center;
            color: #777;
        }

        .pagination {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Arsip Berita PSGA</h1>
    <p>Daftar berita yang sudah diarsipkan dan tidak ditampilkan di website publik.</p>

    <a href="{{ route('berita.index') }}" class="btn btn-secondary">
        ← Kembali ke Kelola Berita
    </a>

    <div class="table-wrapper">
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
                        <td>{{ $berita->firstItem() + $loop->index }}</td>
                        <td>{{ $item->judul }}</td>
                        <td>{{ $item->kategori ?: '-' }}</td>
                        <td>Arsip</td>
                        <td>
                            {{ $item->tanggal_terbit
                            ? \Carbon\Carbon::parse($item->tanggal_terbit)->format('d-m-Y')
                            : '-' }}
                        </td>
                        <td>
                            <div class="aksi">
                                <a href="{{ route('berita.show', $item->slug) }}"
                                   class="btn"
                                   target="_blank">
                                    Lihat Berita
                                </a>

                                <form action="{{ route('berita.pulihkan', $item->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Pulihkan berita ini agar tampil kembali di website?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-restore">
                                        Pulihkan
                                        </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="kosong">
                            Belum ada berita yang diarsipkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $berita->links() }}
    </div>
</div>

</body>
</html>
```