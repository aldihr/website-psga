@php
    $male = (int) $item->male_count;
    $female = (int) $item->female_count;
    $total = $male + $female;
    $malePercent = $total > 0 ? round(($male / $total) * 100, 2) : 0;
    $femalePercent = $total > 0 ? round(($female / $total) * 100, 2) : 0;
    $hasChildren = $item->children && $item->children->isNotEmpty();
@endphp

<article class="gender-card">
    <div class="card-accent"></div>
    <div class="card-header">
        <div class="card-heading">
            <span class="card-kicker">{{ $isRoot ? 'DATA UNIVERSITAS' : ($isSelected ? 'DATA YANG DIPILIH' : 'DATA DI BAWAH ' . (optional($item->parent)->name ?? 'DATA INDUK')) }}</span>
            <h3 class="card-title">{{ $item->name }}</h3>
            @if($item->description)
                <p class="card-subtitle">{{ $item->description }}</p>
            @elseif(!$isRoot)
                <p class="card-subtitle">Bagian dari {{ optional($item->parent)->name ?? 'data utama Universitas' }}</p>
            @endif
        </div>
        <span class="status {{ $item->is_published ? 'status-published' : 'status-draft' }}">
            {{ $item->is_published ? 'Dipublikasikan' : 'Draft' }}
        </span>
    </div>

    <div class="chart-total">
        <span class="chart-total-label">Total responden</span>
        <strong class="chart-total-value">{{ number_format($total, 0, ',', '.') }}</strong>
    </div>

    <div class="chart-wrap">
        <canvas id="gender-chart-{{ $item->id }}"
            data-male="{{ $male }}" data-female="{{ $female }}"
            aria-label="Grafik gender {{ $item->name }}" role="img"></canvas>
    </div>

    <div class="gender-legend">
        <div class="legend-item">
            <span class="legend-label"><span class="legend-dot male-dot"></span>Laki-laki</span>
            <strong class="legend-value">{{ number_format($male, 0, ',', '.') }}</strong>
            <span class="legend-percent">{{ number_format($malePercent, 2, ',', '.') }}%</span>
        </div>
        <div class="legend-item">
            <span class="legend-label"><span class="legend-dot female-dot"></span>Perempuan</span>
            <strong class="legend-value">{{ number_format($female, 0, ',', '.') }}</strong>
            <span class="legend-percent">{{ number_format($femalePercent, 2, ',', '.') }}%</span>
        </div>
    </div>

    <div class="card-actions">
        <a href="{{ route('admin.gender.edit', $item->id) }}" class="btn btn-edit">Edit data</a>
        @unless($item->is_published)
            <form action="{{ route('admin.gender.publish', $item->id) }}" method="POST" class="publish-form">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-publish"><span aria-hidden="true">✦</span> Publikasikan</button>
            </form>
        @endunless
        @if(!$isRoot)
            @if($hasChildren)
                <a href="{{ route('admin.gender.index', ['node_id' => $item->id, 'sort' => $sort]) }}" class="btn btn-detail">Lihat data di bawah <span aria-hidden="true">→</span></a>
            @else
                <a href="{{ route('admin.gender.create', ['parent_id' => $item->id]) }}" class="btn btn-add">+ Tambah data di bawah {{ $item->name }}</a>
            @endif
        @else
            <a href="{{ route('admin.gender.create', ['parent_id' => $item->id]) }}" class="btn btn-add">+ Tambah data di bawah {{ $item->name }}</a>
        @endif
        @if(!$isRoot)
            <form action="{{ route('admin.gender.destroy', $item->id) }}" method="POST" class="delete-form" onsubmit="return confirm('Data ini dan semua data yang berada di bawahnya akan dihapus. Lanjutkan?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-delete">Hapus</button>
            </form>
        @endif
    </div>
</article>
