<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualisasi Gender | Admin PSGA</title>
    <link rel="stylesheet" href="{{ asset('css/admin-gender.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<main class="page">
    <header class="page-header">
        <div class="header-copy">
            <span class="eyebrow">PSGA · UIN SYARIF HIDAYATULLAH JAKARTA</span>
            <h1>Visualisasi Gender</h1>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-back" style="margin-top: 14px;">← Kembali ke Dashboard</a>
        </div>
        <div>
            <a href="{{ route('admin.gender.create') }}" class="btn btn-add header-action">+ Tambah data</a>
        </div>
    </header>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.gender.index') }}" class="sort-form">
        @if($selectedNode)
            <input type="hidden" name="node_id" value="{{ $selectedNode->id }}">
        @endif
        <label for="sort" class="sort-label">Urutkan data</label>
        <select name="sort" id="sort" class="sort-select" onchange="this.form.submit()">
            <option value="custom" @selected($sort === 'custom')>Urutan admin</option>
            <option value="name_asc" @selected($sort === 'name_asc')>Nama A–Z</option>
            <option value="name_desc" @selected($sort === 'name_desc')>Nama Z–A</option>
            <option value="total_desc" @selected($sort === 'total_desc')>Total gender terbanyak</option>
            <option value="male_desc" @selected($sort === 'male_desc')>Laki-laki terbanyak</option>
            <option value="female_desc" @selected($sort === 'female_desc')>Perempuan terbanyak</option>
            <option value="latest" @selected($sort === 'latest')>Baru ditambahkan</option>
            <option value="oldest" @selected($sort === 'oldest')>Paling lama</option>
        </select>
    </form>

    @if(!$university)
        <section class="empty-state">
            <div class="empty-icon">▤</div>
            <h2>Data Universitas belum dibuat</h2>
            <p>Buat Universitas terlebih dahulu. Setelah itu, tambahkan Fakultas di bawahnya, lalu data Mahasiswa di bawah Fakultas.</p>
            <a href="{{ route('admin.gender.create') }}" class="btn btn-add">+ Tambah data universitas</a>
        </section>
    @else
        <section class="level-section root-level">
            <div class="root-card-wrap">
                @include('admin.gender.partials.card', ['item' => $university, 'isRoot' => true, 'isSelected' => false, 'sort' => $sort])
            </div>
        </section>

        @if($selectedNode)
            <div class="hierarchy-connector"><span></span></div>
            <section class="level-section selected-level">
                <div class="section-heading-row">
                    <div>
                        <span class="section-step">DATA YANG DIPILIH</span>
                        <h2 class="section-heading">{{ $selectedNode->name }}</h2>
                        <p class="section-description">Data yang berada langsung di bawah {{ $selectedNode->name }}.</p>
                    </div>
                    <a href="{{ route('admin.gender.index', ['sort' => $sort]) }}" class="btn btn-back">← Semua data</a>
                </div>
                <div class="root-card-wrap">
                    @include('admin.gender.partials.card', ['item' => $selectedNode, 'isRoot' => false, 'isSelected' => true, 'sort' => $sort])
                </div>
            </section>

            <section class="level-section children-level">
                <div class="section-heading-row">
                    <a href="{{ route('admin.gender.create', ['parent_id' => $selectedNode->id]) }}" class="btn btn-add">+ Tambah data di bawah {{ $selectedNode->name }}</a>
                </div>

                @if($nodeChildren->isNotEmpty())
                    <div class="gender-grid">
                        @foreach($nodeChildren as $item)
                            @include('admin.gender.partials.card', ['item' => $item, 'isRoot' => false, 'isSelected' => false, 'sort' => $sort])
                        @endforeach
                    </div>
                @else
                    <div class="empty-state compact-empty">
                        <h3>Belum ada data di bawah {{ $selectedNode->name }}</h3>
                        <p>Contoh: tambahkan Mahasiswa di bawah Fakultas, atau Program Studi di bawah Fakultas.</p>
                        <a href="{{ route('admin.gender.create', ['parent_id' => $selectedNode->id]) }}" class="btn btn-add">+ Tambah data di bawah {{ $selectedNode->name }}</a>
                    </div>
                @endif
            </section>
        @else
            <section class="level-section children-level">
                <div class="section-heading-row">
                    @if($university)
                        <a href="{{ route('admin.gender.create', ['parent_id' => $university->id]) }}" class="btn btn-add">+ Tambah data di bawah {{ $university->name }}</a>
                    @endif
                </div>

                @if($topLevelChildren->isNotEmpty())
                    <div class="gender-grid">
                        @foreach($topLevelChildren as $item)
                            @include('admin.gender.partials.card', ['item' => $item, 'isRoot' => false, 'isSelected' => false, 'sort' => $sort])
                        @endforeach
                    </div>
                @else
                    <div class="empty-state compact-empty">
                        <h3>Belum ada data di bawah {{ $university->name }}</h3>
                        <p>Contoh: tambahkan Fakultas atau Unit di bawah Universitas. Data Mahasiswa dapat ditambahkan di bawah Fakultas.</p>
                        <a href="{{ route('admin.gender.create', ['parent_id' => $university->id]) }}" class="btn btn-add">+ Tambah data pertama</a>
                    </div>
                @endif
            </section>
        @endif

        <section class="data-table-wrapper">
            <div class="table-heading">
                <div>
                    <span class="section-step">RINGKASAN</span>
                    <h2>Ringkasan Data Gender</h2>
                </div>
                <span class="record-count">{{ $summaryRows->count() }} data</span>
            </div>
            <div class="table-scroll">
                <table>
                    <thead><tr><th>Nama data</th><th>Bagian dari</th><th>Laki-laki</th><th>Perempuan</th><th>Total</th><th>Aksi</th></tr></thead>
                    <tbody>
                    @foreach($summaryRows as $item)
                        @php
                            $total = (int) $item->male_count + (int) $item->female_count;
                        @endphp
                        <tr>
                            <td><strong>{{ $item->name }}</strong></td>
                            <td>{{ optional($item->parent)->name ?? 'Data utama Universitas' }}</td>
                            <td>{{ number_format($item->male_count, 0, ',', '.') }}</td>
                            <td>{{ number_format($item->female_count, 0, ',', '.') }}</td>
                            <td><strong>{{ number_format($total, 0, ',', '.') }}</strong></td>
                            <td><div class="table-actions">
                                <a href="{{ route('admin.gender.edit', $item->id) }}" class="btn btn-edit">Edit</a>
                                @if($item->children->isNotEmpty())
                                <a href="{{ route('admin.gender.index', ['node_id' => $item->id, 'sort' => $sort]) }}" class="btn btn-detail">Lihat data di bawah</a>
                                @endif
                                @if((int) $item->id !== (int) $university->id)
                                    <form action="{{ route('admin.gender.destroy', $item->id) }}" method="POST" class="delete-form" onsubmit="return confirm('Data ini dan semua data yang berada di bawahnya akan dihapus. Lanjutkan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete">Hapus</button>
                                    </form>
                                @endif
                            </div></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('canvas[id^="gender-chart-"]').forEach(function (canvas) {
        const male = Number(canvas.dataset.male || 0);
        const female = Number(canvas.dataset.female || 0);
        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{
                    data: [male, female],
                    backgroundColor: ['#3478d4', '#e56b9a'],
                    borderColor: ['#ffffff', '#ffffff'],
                    borderWidth: 5,
                    hoverOffset: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        padding: 12,
                        callbacks: {
                            label: function (context) {
                                const value = Number(context.raw || 0);
                                const total = male + female;
                                const pct = total > 0 ? (value / total * 100).toFixed(2) : '0.00';
                                return ' ' + context.label + ': ' + value.toLocaleString('id-ID') + ' (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    });
});
</script>
</body>
</html>

