
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin PSGA</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f3f9;
            margin: 0;
            padding: 30px;
            color: #292334;
        }

        .container {
            max-width: 1450px;
            margin: auto;
        }

        .page-header {
            margin-bottom: 25px;
        }

        h1 {
            color: #6a1b9a;
            font-size: 30px;
            margin: 0 0 8px;
        }

        .subtitle {
            color: #777;
            line-height: 1.6;
        }

        /* KARTU STATISTIK */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: white;
            padding: 22px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(40, 20, 60, .06);
            border-left: 5px solid #7b1fa2;
        }

        .stat-card.new {
            border-left-color: #e5a000;
        }

        .stat-card.process {
            border-left-color: #2878d0;
        }

        .stat-card.done {
            border-left-color: #20a464;
        }

        .stat-label {
            color: #777;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .stat-number {
            color: #4a1769;
            font-size: 30px;
            font-weight: bold;
        }

        .stat-description {
            color: #999;
            font-size: 12px;
            margin-top: 8px;
        }

        /* PANEL TABEL */
        .panel {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 3px 12px rgba(40, 20, 60, .06);
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        .panel-header h2 {
            color: #6a1b9a;
            margin: 0;
            font-size: 22px;
        }

        .toolbar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .toolbar input,
        .toolbar select {
            padding: 11px 13px;
            border: 1px solid #ddd;
            border-radius: 7px;
            font-size: 14px;
            background: white;
        }

        .toolbar input {
            width: 260px;
            max-width: 100%;
        }

        .toolbar input:focus,
        .toolbar select:focus {
            outline: none;
            border-color: #8e24aa;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 13px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 1200px;
            border-collapse: collapse;
        }

        th {
            background: #6a1b9a;
            color: white;
            padding: 15px 12px;
            text-align: left;
            font-size: 13px;
            white-space: nowrap;
        }

        td {
            padding: 14px 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
            vertical-align: top;
            line-height: 1.6;
        }

        tbody tr:hover {
            background: #faf7fd;
        }

        .complaint-text {
            min-width: 180px;
            max-width: 260px;
            white-space: normal;
            overflow-wrap: anywhere;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            background: #fff0c2;
            color: #865900;
            font-size: 12px;
            font-weight: bold;
        }

        .status.selesai {
            background: #d9f5e5;
            color: #187746;
        }

        .status.verifikasi {
            background: #dcecff;
            color: #205ba5;
        }

        .status.pendalaman,
        .status.tindak-lanjut {
            background: #eee1ff;
            color: #6a1b9a;
        }

        .action-buttons {
            min-width: 100px;
        }

        .btn {
            display: block;
            width: 88px;
            padding: 9px 5px;
            text-align: center;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            font-size: 12px;
            margin-bottom: 8px;
            border: none;
        }

        .btn-proses {
            background: #7b1fa2;
        }

        .btn-proses:hover {
            background: #581580;
        }

        .btn-whatsapp {
            background: #20bd5a;
        }

        .btn-whatsapp:hover {
            background: #159447;
        }

        .empty-row {
            text-align: center;
            padding: 35px;
            color: #888;
        }

        .table-footer {
            margin-top: 16px;
            color: #777;
            font-size: 13px;
        }

        .pagination-wrapper {
            margin-top: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 14px;
        }

        .pagination-wrapper nav {
            display: flex;
            justify-content: center;
        }

        .pagination-wrapper svg {
            width: 18px;
            height: 18px;
        }

        .pagination-wrapper a,
        .pagination-wrapper span {
            padding: 8px 12px;
            border-radius: 6px;
        }

        @media (max-width: 900px) {
            body {
                padding: 16px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .panel {
                padding: 16px;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            h1 {
                font-size: 24px;
            }

            .toolbar,
            .toolbar input,
            .toolbar select {
                width: 100%;
            }
        }

    </style>
</head>

<body>

<div class="container">

    <div class="page-header">
        <h1>Dashboard Admin PSGA</h1>

        <div class="subtitle">
            Pusat Studi Gender dan Anak UIN Syarif Hidayatullah Jakarta
            <br>
            Panel pengelolaan layanan pengaduan.
        </div>
    </div>

    @if(session('success'))
        <div class="success">
            ✓ {{ session('success') }}
        </div>
    @endif
    <div style="margin-bottom: 18px;">
    <a href="{{ url('/admin/dashboard') }}"
       style="display: inline-block;
              background: #6b1c91;
              color: white;
              padding: 10px 18px;
              border-radius: 8px;
              text-decoration: none;
              font-weight: 600;
              font-size: 14px;">
        ← Kembali ke Beranda
    </a>
</div>

    <!-- STATISTIK -->
    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-label">Total Pengaduan</div>
            <div class="stat-number">{{ $totalPengaduan }}</div>
            <div class="stat-description">Seluruh data pengaduan</div>
        </div>

        <div class="stat-card new">
            <div class="stat-label">Pengajuan Baru</div>
            <div class="stat-number">{{ $pengajuanBaru }}</div>
            <div class="stat-description">Menunggu proses awal</div>
        </div>

        <div class="stat-card process">
            <div class="stat-label">Sedang Diproses</div>
            <div class="stat-number">{{ $sedangDiproses }}</div>
            <div class="stat-description">Belum berstatus selesai</div>
        </div>

        <div class="stat-card done">
            <div class="stat-label">Pengaduan Selesai</div>
            <div class="stat-number">{{ $pengaduanSelesai }}</div>
            <div class="stat-description">Telah ditandai selesai</div>
        </div>

    </div>

    <!-- TABEL PENGADUAN -->

<div class="panel" style="margin-bottom: 20px;">
    <div class="panel-header">
        <h2>Grafik Kategori Pengaduan</h2>
    </div>

    <form method="GET"
          action="{{ route('pengaduan.admin') }}"
          style="display:flex; gap:12px; align-items:end; flex-wrap:wrap; margin-bottom:20px;">

        <div>
            <label for="grafik_mode">Periode Grafik</label>
<select name="grafik_mode" id="grafik_mode">
    <option value="bulanan" {{ ($grafikMode ?? 'bulanan') === 'bulanan' ? 'selected' : '' }}>
        Bulanan
    </option>
    <option value="tahunan" {{ ($grafikMode ?? 'bulanan') === 'tahunan' ? 'selected' : '' }}>
        Tahunan
    </option>
</select>
        </div>

        <div>
            <label for="grafik_tahun">Tahun</label>
            <select name="grafik_tahun" id="grafik_tahun">
                @for ($tahun = now()->year; $tahun >= now()->year - 4; $tahun--)
                    <option value="{{ $tahun }}" {{ (int) $grafikTahun === $tahun ? 'selected' : '' }}>
                        {{ $tahun }}
                    </option>
                @endfor
            </select>
        </div>

        {{-- Pertahankan filter tabel ketika periode grafik diubah --}}
        <input type="hidden" name="search" value="{{ request('search') }}">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="hidden" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
        <input type="hidden" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">

        <button type="submit" class="btn-search">Tampilkan Grafik</button>
    </form>

    <div style="position:relative; width:100%; height:350px;">
        <canvas id="grafikKategori"></canvas>
    </div>

    <div id="kategoriDominan"
         style="margin-top:16px; font-weight:600;">
    </div>
</div>

    <div class="panel">
        <div class="panel-header">
            <h2>Data Pengaduan</h2>
           
<form method="GET"
         action="{{ route('pengaduan.admin') }}"
         class="toolbar">

         <input
        type="search"
        name="search"
        placeholder="Cari nama, email, nomor tiket, atau ID..."
        value="{{ request('search') }}"
        aria-label="Cari data pengaduan"
          >

    <select name="status" aria-label="Filter status">
        <option value="">Semua Status</option>

        <option value="Pengajuan"
            {{ request('status') === 'Pengajuan' ? 'selected' : '' }}>
            Pengajuan
        </option>

        <option value="Verifikasi"
            {{ request('status') === 'Verifikasi' ? 'selected' : '' }}>
            Verifikasi
        </option>

        <option value="Pendalaman"
            {{ request('status') === 'Pendalaman' ? 'selected' : '' }}>
            Pendalaman
        </option>

        <option value="Tindak Lanjut"
            {{ request('status') === 'Tindak Lanjut' ? 'selected' : '' }}>
            Tindak Lanjut
        </option>

        <option value="Selesai"
            {{ request('status') === 'Selesai' ? 'selected' : '' }}>
            Selesai
        </option>
    </select>
    
<label for="tanggal_mulai" style="font-size: 13px;">
    Tanggal Mulai
    <input
        type="date"
        id="tanggal_mulai"
        name="tanggal_mulai"
        value="{{ request('tanggal_mulai') }}"
        aria-label="Tanggal mulai"
    >
</label>

<label for="tanggal_akhir" style="font-size: 13px;">
    Tanggal Akhir
    <input
        type="date"
        id="tanggal_akhir"
        name="tanggal_akhir"
        value="{{ request('tanggal_akhir') }}"
        min="{{ request('tanggal_mulai') }}"
        aria-label="Tanggal akhir"
    >
</label>


    <button type="submit" class="btn-search">Cari</button>

    @if(
    request()->filled('search') ||
    request()->filled('status') ||
    request()->filled('tanggal_mulai') ||
    request()->filled('tanggal_akhir')
)
    <a href="{{ route('pengaduan.admin') }}" class="btn-reset">
        Reset
    </a>
    @endif

</form>

<div style="display: flex; gap: 10px; flex-wrap: wrap; margin: 15px 0;">

    <a href="{{ route('pengaduan.export.excel', request()->query()) }}"
       style="display: inline-block; background: #198754; color: white;
              padding: 10px 16px; border-radius: 6px;
              text-decoration: none; font-weight: bold;">
        Export Excel
    </a>

    <a href="{{ route('pengaduan.export.pdf', request()->query()) }}"
       style="display: inline-block; background: #A10F79; color: white;
              padding: 10px 16px; border-radius: 6px;
              text-decoration: none; font-weight: bold;">
        Export PDF
    </a>

</div>
    </div>


        <div class="table-wrapper">
            <table id="complaintTable">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>No. Tiket</th>
                        <th>Nama Pelapor</th>
                        <th>Terlapor</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Fakultas</th>
                        <th>Kategori Pengaduan</th>
                        <th>Isi Pengaduan</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pengaduans as $pengaduan)

                        @php
                            $nomorWa = preg_replace(
                                '/^0/',
                                '62',
                                preg_replace('/[^0-9]/', '', $pengaduan->no_hp)
                            );

                            $pesanWa =
                                'Halo ' . $pengaduan->nama_pelapor . ',' . "\n\n" .
                                'Pengaduan Anda di PSGA UIN Syarif Hidayatullah Jakarta telah diperbarui.' . "\n" .
                                'No. Tiket: ' . $pengaduan->no_ticket . "\n" .
                                'Status: ' . $pengaduan->status . "\n" .
                                'Tanggapan: ' . ($pengaduan->tanggapan ?? '-') . "\n\n" .
                                'Silakan cek status pengaduan Anda melalui sistem Tracking Pengaduan PSGA.';

                            $statusClass = strtolower(
                                str_replace(' ', '-', $pengaduan->status)
                            );
                        @endphp

                        <tr
                            class="complaint-row"
                            data-status="{{ $pengaduan->status }}"
                        >
                            <td>{{ $pengaduan->id }}</td>
                        <td>
                            <strong>{{ $pengaduan->no_ticket ?? '-' }}</strong>
                        </td>
                            <td>{{ $pengaduan->nama_pelapor }}</td>

                            <td>{{ $pengaduan->terlapor ?? '-' }}</td>

                            <td>{{ $pengaduan->email_pelapor }}</td>

                            <td>{{ $pengaduan->no_hp }}</td>

                            <td>{{ $pengaduan->fakultas }}</td>

                            <td class="complaint-text">
                                {{ $pengaduan->kategori_pengaduan }}
                            </td>

                            <td class="complaint-text">
                                {{ $pengaduan->isi_pengaduan }}
                            </td>

                            <td>
                                <span class="status {{ $statusClass }}">
                                    {{ $pengaduan->status }}
                                </span>
                            </td>

                            <td>
                                {{ $pengaduan->created_at
                                    ? $pengaduan->created_at->format('d-m-Y H:i')
                                    : '-' }}
                            </td>

                            <td class="action-buttons">

                                <a
                                    href="{{ route('pengaduan.edit', $pengaduan->id) }}"
                                    class="btn btn-proses"
                                >
                                    Proses
                                </a>

                                <a
                                    href="https://wa.me/{{ $nomorWa }}?text={{ urlencode($pesanWa) }}"
                                    class="btn btn-whatsapp"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    WhatsApp
                                </a>

                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="11" class="empty-row">
                                Belum ada data pengaduan.
                            </td>
                        </tr>
                    @endforelse

                    <tr id="noResults" style="display: none;">
                        <td colspan="10" class="empty-row">
                            Data pengaduan tidak ditemukan.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
           {{ $pengaduans->onEachSide(1)->links('pagination::simple-default') }}
        </div>

        <div class="table-footer" id="tableCount">
            Menampilkan {{ $totalPengaduan }} data pengaduan.
        </div>

    </div>

</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const rows = document.querySelectorAll('.complaint-row');
    const noResults = document.getElementById('noResults');
    const tableCount = document.getElementById('tableCount');

    function filterComplaints() {
        const keyword = searchInput.value.toLowerCase().trim();
        const selectedStatus = statusFilter.value;

        let visibleCount = 0;

        rows.forEach(function(row) {
            const rowText = row.innerText.toLowerCase();
            const rowStatus = row.dataset.status;

            const matchesKeyword = rowText.includes(keyword);
            const matchesStatus =
                selectedStatus === '' ||
                rowStatus === selectedStatus ||
                (
                    selectedStatus === 'Pengajuan' &&
                    rowStatus === 'Pengajuan Pengaduan'
                );

            if (matchesKeyword && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        noResults.style.display =
            visibleCount === 0 ? '' : 'none';

        tableCount.textContent =
            'Menampilkan ' + visibleCount +
            ' dari ' + rows.length + ' data pengaduan.';
    }

    searchInput.addEventListener('input', filterComplaints);
    statusFilter.addEventListener('change', filterComplaints);
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('grafikKategori');

    if (!canvas || typeof Chart === 'undefined') {
        return;
    }

    const labels = @json($labelGrafik);
    const datasets = @json($datasetsGrafik);
    const mode = @json($grafikMode ?? 'bulanan');

    const chart = new Chart(canvas, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets.map(function (item) {
                return {
                    label: item.label,
                    data: item.data,
                    backgroundColor: item.backgroundColor,
                    borderColor: item.backgroundColor,
                    borderWidth: 1,
                    borderRadius: 2,
                    maxBarThickness: 38
                };
            })
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: false,
                    title: {
                        display: true,
                        text: mode === 'bulanan' ? 'Bulan' : 'Tahun'
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    title: {
                        display: true,
                        text: 'Jumlah Pengaduan'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return context.dataset.label + ': ' +
                                context.parsed.y + ' laporan';
                        }
                    }
                }
            }
        }
    });

    const totalPerKategori = datasets.map(function (item) {
        return {
            label: item.label,
            total: item.data.reduce(function (sum, value) {
                return sum + Number(value || 0);
            }, 0)
        };
    });

    const totalLaporan = totalPerKategori.reduce(function (sum, item) {
        return sum + item.total;
    }, 0);

    const dominan = totalPerKategori.reduce(function (terbesar, item) {
        return item.total > terbesar.total ? item : terbesar;
    }, { label: '-', total: 0 });

    const keterangan = document.getElementById('kategoriDominan');

    if (totalLaporan === 0) {
        keterangan.textContent =
            'Belum ada data kategori pada periode yang dipilih.';
    } else {
        keterangan.textContent =
            'Kategori paling dominan: ' + dominan.label +
            ' (' + dominan.total + ' laporan). Total laporan pada grafik: ' +
            totalLaporan + '.';
    }
});
</script>


</body>
</html>
