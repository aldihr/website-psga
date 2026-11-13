<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Visualisasi Gender | PSGA UIN Jakarta</title>

    <link rel="stylesheet" href="{{ asset('css/user-gender.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
<main class="page-container">

    {{-- HEADER --}}
    <header class="page-intro">
        <div class="intro-copy">
            <p class="eyebrow">PSGA · UIN SYARIF HIDAYATULLAH JAKARTA</p>
            <h1>Visualisasi Gender</h1>

            <p class="intro-description">
                Jelajahi persebaran data gender di lingkungan universitas
                berdasarkan fakultas, unit, dan kategori turunannya.
            </p>
            <a class="home-link" href="{{ url('/') }}">← Kembali ke Beranda</a>
        </div>

        <div class="intro-mark">
            <img
                src="{{ asset('images/PSGA.jpg') }}"
                alt="Logo PSGA UIN Syarif Hidayatullah Jakarta"
            >
        </div>
    </header>

    {{-- PENCARIAN DAN SORTING --}}
    <section class="gender-toolbar" aria-label="Pencarian dan pengurutan data">
        <div class="gender-search">
            <label for="genderSearch">Cari kategori</label>

            <div class="search-input-wrap">
                <span class="search-icon" aria-hidden="true">⌕</span>

                <input
                    type="search"
                    id="genderSearch"
                    placeholder="Cari universitas, fakultas, atau kategori..."
                    autocomplete="off"
                >

                <button
                    type="button"
                    id="clearGenderSearch"
                    class="clear-search"
                    aria-label="Hapus pencarian"
                    hidden
                >×</button>
            </div>
        </div>

        <div class="gender-sort">
            <label for="genderSort">Urutkan berdasarkan</label>

            <select id="genderSort">
                <option value="default">Urutan awal</option>
                <option value="name-asc">Nama kategori (A–Z)</option>
                <option value="name-desc">Nama kategori (Z–A)</option>
                <option value="male-desc">Laki-laki terbanyak</option>
                <option value="female-desc">Perempuan terbanyak</option>
                <option value="total-desc">Total data terbanyak</option>
                <option value="total-asc">Total data tersedikit</option>
            </select>
        </div>

        <button type="button" id="resetGenderFilter" class="reset-filter">
            Reset filter
        </button>
    </section>

    <p id="genderFilterStatus" class="filter-status" aria-live="polite"></p>

    @if(!$university)

        {{-- DATA UNIVERSITAS BELUM TERSEDIA --}}
        <section class="empty-state">
            <div class="empty-icon">▤</div>
            <h3>Data belum tersedia</h3>
            <p>
                Visualisasi gender belum memiliki data universitas
                untuk ditampilkan.
            </p>
        </section>

    @else

        {{-- DATA UNIVERSITAS --}}
        <section class="level-section root-level">
            <div class="section-heading">
                <div>
                    <h2>Data Induk</h2>
                </div>

                <span class="result-count">Data induk</span>
            </div>

            <div class="root-card-wrap">
                @include('gender.partials.card', [
                    'item' => $university,
                    'isRoot' => true,
                    'isSelected' => false
                ])
            </div>
        </section>

        @if($selectedNode)

            {{-- BREADCRUMB --}}
            <nav class="breadcrumb" aria-label="Jalur kategori">
                <a href="{{ route('gender.index', ['sort' => $sort]) }}">
                    Universitas
                </a>

                @foreach($ancestorPath->slice(1) as $pathItem)
                    <span aria-hidden="true">›</span>

                    @if(!$loop->last)
                        <a href="{{ route('gender.index', [
                            'node_id' => $pathItem->id,
                            'sort' => $sort
                        ]) }}">
                            {{ $pathItem->name }}
                        </a>
                    @else
                        <strong>{{ $pathItem->name }}</strong>
                    @endif
                @endforeach
            </nav>

            {{-- KATEGORI TERPILIH --}}
            <section class="level-section selected-level">
                <div class="section-heading">
                    <div>
                        <span class="section-step">KATEGORI TERPILIH</span>
                        <h2>{{ $selectedNode->name }}</h2>
                    </div>

                    <a
                        href="{{ route('gender.index', ['sort' => $sort]) }}"
                        class="text-link"
                    >
                        ← Semua kategori
                    </a>
                </div>

                <div class="root-card-wrap">
                    @include('gender.partials.card', [
                        'item' => $selectedNode,
                        'isRoot' => false,
                        'isSelected' => true
                    ])
                </div>
            </section>

            {{-- TURUNAN KATEGORI --}}
            <section class="level-section">
                <div class="section-heading">
                    <div>
                        <h2>Turunan {{ $selectedNode->name }}</h2>
                    </div>

                    <span class="result-count">
                        {{ $nodeChildren->count() }} kategori
                    </span>
                </div>

                @if($nodeChildren->isNotEmpty())
                    <div class="gender-grid">
                        @foreach($nodeChildren as $item)
                            @include('gender.partials.card', [
                                'item' => $item,
                                'isRoot' => false,
                                'isSelected' => false
                            ])
                        @endforeach
                    </div>
                @else
                    <div class="empty-state compact-empty">
                        <h3>Ini merupakan tingkat terakhir</h3>
                        <p>
                            Belum ada kategori turunan di bawah
                            {{ $selectedNode->name }}.
                        </p>
                    </div>
                @endif
            </section>

        @else

            {{-- FAKULTAS / KATEGORI TINGKAT PERTAMA --}}
            <section class="level-section">
                <div class="section-heading">
                    <div>
                        <h2>Fakultas dan Unit</h2>
                    </div>

                    <span class="result-count">
                        {{ $topLevelChildren->count() }} kategori
                    </span>
                </div>

                @if($topLevelChildren->isNotEmpty())
                    <div class="gender-grid">
                        @foreach($topLevelChildren as $item)
                            @include('gender.partials.card', [
                                'item' => $item,
                                'isRoot' => false,
                                'isSelected' => false
                            ])
                        @endforeach
                    </div>
                @else
                    <div class="empty-state compact-empty">
                        <h3>Belum ada kategori</h3>
                        <p>
                            Data fakultas atau kategori turunan belum tersedia.
                        </p>
                    </div>
                @endif
            </section>

        @endif

        {{-- TABEL RINGKASAN --}}
        <section class="data-table-wrapper">
            <div class="section-heading">
                <div>
                    <h2>Ringkasan Data Gender</h2>
                    <p>
                        @if($selectedNode)
                            Menampilkan jalur kategori terpilih
                        @else
                            Menampilkan universitas dan kategori
                            tingkat pertama.
                        @endif
                    </p>
                </div>

                <span class="result-count">
                    {{ $summaryRows->count() }} data
                </span>
            </div>

            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Nama kategori</th>
                            <th>Jalur kategori</th>
                            <th>Laki-laki</th>
                            <th>Perempuan</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($summaryRows as $item)
                            @php
                                $total = (int) $item->male_count
                                    + (int) $item->female_count;

                                $parts = [];
                                $current = $item;
                                $seen = [];

                                while (
                                    $current &&
                                    !isset($seen[$current->id])
                                ) {
                                    $seen[$current->id] = true;
                                    array_unshift($parts, $current->name);
                                    $current = $current->parent;
                                }

                                $searchText = implode(' ', $parts);
                            @endphp

                            <tr
                                class="gender-summary-row"
                                data-search="{{ \Illuminate\Support\Str::lower($item->name . ' ' . $searchText) }}"
                                data-name="{{ $item->name }}"
                                data-male="{{ (int) $item->male_count }}"
                                data-female="{{ (int) $item->female_count }}"
                                data-total="{{ $total }}"
                            >
                                <td>
                                    <strong>{{ $item->name }}</strong>
                                </td>

                                <td>{{ implode(' › ', $parts) }}</td>

                                <td>
                                    {{ number_format($item->male_count, 0, ',', '.') }}
                                </td>

                                <td>
                                    {{ number_format($item->female_count, 0, ',', '.') }}
                                </td>

                                <td>
                                    <strong>
                                        {{ number_format($total, 0, ',', '.') }}
                                    </strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-cell">
                                    Belum ada data untuk ditampilkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    @endif


</main>

{{-- GRAFIK CHART.JS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('canvas[id^="gender-chart-"]').forEach(function (canvas) {
        const male = Number(canvas.dataset.male || 0);
        const female = Number(canvas.dataset.female || 0);
        const total = male + female;

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
                    legend: {
                        display: false
                    },
                    tooltip: {
                        padding: 12,
                        callbacks: {
                            label: function (context) {
                                const value = Number(context.raw || 0);
                                const percentage = total > 0
                                    ? (value / total * 100).toFixed(2)
                                    : '0.00';

                                return ' ' + context.label + ': '
                                    + value.toLocaleString('id-ID')
                                    + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    });

    // PENCARIAN DAN SORTING
    const searchInput = document.getElementById('genderSearch');
    const sortSelect = document.getElementById('genderSort');
    const clearButton = document.getElementById('clearGenderSearch');
    const resetButton = document.getElementById('resetGenderFilter');
    const status = document.getElementById('genderFilterStatus');

    if (!searchInput || !sortSelect) return;

    const cards = Array.from(
        document.querySelectorAll('.gender-card[data-search]')
    );

    const rows = Array.from(
        document.querySelectorAll('.gender-summary-row')
    );

    const originalCardPositions = new Map(
        cards.map((element, index) => [element, index])
    );

    const originalRowPositions = new Map(
        rows.map((element, index) => [element, index])
    );

    function normalize(value) {
        return String(value || '')
            .toLocaleLowerCase('id-ID')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .trim();
    }

    function compareItems(a, b, sortType, positions) {
        const nameA = normalize(a.dataset.name);
        const nameB = normalize(b.dataset.name);

        const maleA = Number(a.dataset.male || 0);
        const maleB = Number(b.dataset.male || 0);

        const femaleA = Number(a.dataset.female || 0);
        const femaleB = Number(b.dataset.female || 0);

        const totalA = Number(a.dataset.total || 0);
        const totalB = Number(b.dataset.total || 0);

        switch (sortType) {
            case 'name-asc':
                return nameA.localeCompare(nameB, 'id');

            case 'name-desc':
                return nameB.localeCompare(nameA, 'id');

            case 'male-desc':
                return maleB - maleA;

            case 'female-desc':
                return femaleB - femaleA;

            case 'total-desc':
                return totalB - totalA;

            case 'total-asc':
                return totalA - totalB;

            default:
                return positions.get(a) - positions.get(b);
        }
    }

    function applyFilters() {
        const keyword = normalize(searchInput.value);
        const sortType = sortSelect.value;

        clearButton.hidden = !searchInput.value;

        let visibleCards = 0;
        let visibleRows = 0;

        // Filter kartu grafik.
        cards.forEach(function (card) {
            const matches = normalize(card.dataset.search).includes(keyword);
            card.hidden = !matches;

            if (matches) visibleCards++;
        });

        // Sortir kartu hanya di dalam grid masing-masing.
        document.querySelectorAll('.gender-grid').forEach(function (grid) {
            const gridCards = Array.from(grid.children).filter(function (element) {
                return element.matches('.gender-card[data-search]');
            });

            gridCards.sort(function (a, b) {
                return compareItems(
                    a,
                    b,
                    sortType,
                    originalCardPositions
                );
            });

            gridCards.forEach(function (card) {
                grid.appendChild(card);
            });
        });

        // Filter dan sortir tabel.
        const tableBody = document.querySelector('.table-scroll tbody');

        if (tableBody) {
            rows.forEach(function (row) {
                const matches = normalize(row.dataset.search).includes(keyword);
                row.hidden = !matches;

                if (matches) visibleRows++;
            });

            const sortedRows = [...rows].sort(function (a, b) {
                return compareItems(
                    a,
                    b,
                    sortType,
                    originalRowPositions
                );
            });

            sortedRows.forEach(function (row) {
                tableBody.appendChild(row);
            });
        }

        if (status) {
            if (keyword) {
                status.textContent =
                    'Pencarian "' + searchInput.value.trim() + '": '
                    + visibleCards + ' kartu dan '
                    + visibleRows + ' baris tabel ditemukan.';
            } else {
                status.textContent =
                    visibleCards + ' kartu dan '
                    + visibleRows + ' baris tabel tersedia.';
            }
        }
    }

    searchInput.addEventListener('input', applyFilters);
    sortSelect.addEventListener('change', applyFilters);

    clearButton.addEventListener('click', function () {
        searchInput.value = '';
        searchInput.focus();
        applyFilters();
    });

    resetButton.addEventListener('click', function () {
        searchInput.value = '';
        sortSelect.value = 'default';
        applyFilters();
    });

    applyFilters();
});
</script>

</body>
</html>
