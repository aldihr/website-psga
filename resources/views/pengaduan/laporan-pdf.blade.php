
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengaduan PSGA</title>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #A10F79;
            margin-bottom: 5px;
        }

        p {
            text-align: center;
            margin-bottom: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #999;
            padding: 5px;
            vertical-align: top;
        }

        th {
            background-color: #A10F79;
            color: white;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f7f0f5;
        }

        .footer {
            margin-top: 15px;
            text-align: right;
            font-size: 8px;
        }
    </style>
</head>
<body>

    <h2>LAPORAN PENGADUAN PSGA</h2>
    <p>Pusat Studi Gender dan Anak</p>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Tiket</th>
                <th>Nama Pelapor</th>
                <th>Terlapor</th>
                <th>Judul Pengaduan</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengaduans as $pengaduan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pengaduan->no_ticket }}</td>
                    <td>{{ $pengaduan->nama_pelapor }}</td>
                    <td>{{ $pengaduan->terlapor }}</td>
                    <td>{{ $pengaduan->judul_pengaduan }}</td>
                    <td>{{ $pengaduan->status }}</td>
                    <td>
                        {{ $pengaduan->created_at
                            ? $pengaduan->created_at->format('d-m-Y')
                            : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;">
                        Tidak ada data pengaduan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Total pengaduan: {{ $pengaduans->count() }} data
    </div>

</body>
</html>
