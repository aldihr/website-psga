@php
    $male = (int) ($item->male_count ?? 0);
    $female = (int) ($item->female_count ?? 0);
    $total = $male + $female;

    $malePercent = $total > 0
        ? round(($male / $total) * 100, 2)
        : 0;

    $femalePercent = $total > 0
        ? round(($female / $total) * 100, 2)
        : 0;

    $hasChildren = $item->children && $item->children->isNotEmpty();

    $parentName = optional($item->parent)->name ?? 'kategori induk';

    $searchText = \Illuminate\Support\Str::lower(
        $item->name . ' ' .
        ($item->description ?? '') . ' ' .
        $parentName
    );
@endphp

<article
    class="gender-card"
    data-search="{{ $searchText }}"
    data-name="{{ $item->name }}"
    data-male="{{ $male }}"
    data-female="{{ $female }}"
    data-total="{{ $total }}"
>
    <div class="card-topline">
        <span class="article-category">
            @if($isRoot)
                DATA GENDER
            @elseif($isSelected)
                KATEGORI TERPILIH
            @else
                DATA TURUNAN
            @endif
        </span>
    </div>

    <h3>{{ $item->name }}</h3>

    @if(!empty($item->description))
        <p class="card-description">
            {{ $item->description }}
        </p>
    @elseif(!$isRoot)
        <p class="card-description">
            Bagian dari {{ $parentName }}.
        </p>
    @endif

    <div class="chart-total">
        <span>Total data</span>
        <strong>{{ number_format($total, 0, ',', '.') }}</strong>
    </div>

    <div class="chart-wrap">
        <canvas
            id="gender-chart-{{ $item->id }}"
            data-male="{{ $male }}"
            data-female="{{ $female }}"
            aria-label="Grafik gender {{ $item->name }}"
            role="img"
        ></canvas>
    </div>

    <div class="gender-legend">
        <div class="legend-item">
            <span class="legend-label">
                <span class="legend-dot male-dot"></span>
                Laki-laki
            </span>

            <strong>{{ number_format($male, 0, ',', '.') }}</strong>

            <span class="legend-percent">
                {{ number_format($malePercent, 2, ',', '.') }}%
            </span>
        </div>

        <div class="legend-item">
            <span class="legend-label">
                <span class="legend-dot female-dot"></span>
                Perempuan
            </span>

            <strong>{{ number_format($female, 0, ',', '.') }}</strong>

            <span class="legend-percent">
                {{ number_format($femalePercent, 2, ',', '.') }}%
            </span>
        </div>
    </div>

    <div class="card-footer">
        @if($isRoot)
            <span>Data induk</span>

        @elseif($hasChildren)
            <span>
                {{ $item->children->count() }} turunan langsung
            </span>

            <a
                href="{{ route('gender.index', [
                    'node_id' => $item->id,
                    'sort' => request('sort', $sort ?? 'default')
                ]) }}"
                aria-label="Lihat turunan {{ $item->name }}"
            >
                Tampilkan Detail <span aria-hidden="true">→</span>
            </a>

        @else
            <span>Kategori tingkat akhir</span>
        @endif
    </div>
</article>
