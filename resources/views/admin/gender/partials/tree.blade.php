@if(isset($nodes) && $nodes->isNotEmpty())
    <div class="gender-tree-list">
        @foreach($nodes as $node)
            @php
                $maleCount = (int) ($node->male_count ?? 0);
                $femaleCount = (int) ($node->female_count ?? 0);
                $totalCount = $maleCount + $femaleCount;

                $malePercentage = $totalCount > 0
                    ? round(($maleCount / $totalCount) * 100, 2)
                    : 0;

                $femalePercentage = $totalCount > 0
                    ? round(($femaleCount / $totalCount) * 100, 2)
                    : 0;

                $nodePath = $node->hierarchy_path ?? $node->name;
                $hasChildren = isset($node->children)
                    && $node->children->isNotEmpty();
            @endphp

            <article class="gender-tree-node">
                <div class="chart-card">

                    <div class="chart-card-header">
                        <div>
                            <span class="hierarchy-label">
                                {{ $nodePath }}
                            </span>

                            <h3>{{ $node->name }}</h3>

                            @if($node->description)
                                <p class="chart-subtitle">
                                    {{ $node->description }}
                                </p>
                            @endif
                        </div>

                        <span class="status-badge {{ $node->is_published ? 'status-active' : 'status-draft' }}">
                            {{ $node->is_published ? 'Aktif' : 'Draft' }}
                        </span>
                    </div>

                    <div class="chart-container">
                        <canvas
                            id="gender-chart-{{ $node->id }}"
                            aria-label="Grafik gender {{ $node->name }}"
                            role="img">
                        </canvas>
                    </div>

                    <div class="chart-legend">
                        <div class="legend-item">
                            <span class="legend-dot male-dot"></span>

                            <div>
                                <span>Laki-laki</span>
                                <strong>{{ number_format($maleCount, 0, ',', '.') }}</strong>
                                <small>{{ number_format($malePercentage, 2, ',', '.') }}%</small>
                            </div>
                        </div>

                        <div class="legend-item">
                            <span class="legend-dot female-dot"></span>

                            <div>
                                <span>Perempuan</span>
                                <strong>{{ number_format($femaleCount, 0, ',', '.') }}</strong>
                                <small>{{ number_format($femalePercentage, 2, ',', '.') }}%</small>
                            </div>
                        </div>
                    </div>

                    <div class="chart-total">
                        <span>Total</span>
                        <strong>{{ number_format($totalCount, 0, ',', '.') }}</strong>
                    </div>

                    <div class="chart-actions">
                        <a
                            href="{{ route('admin.gender.edit', $node->id) }}"
                            class="btn-edit">
                            Edit
                        </a>

                        @if($hasChildren)
                            <a
                                href="{{ route('admin.gender.index', ['faculty_id' => $node->id]) }}"
                                class="btn-edit">
                                Lihat Turunan
                            </a>
                        @else
                            <a
                                href="{{ route('admin.gender.create', ['parent_id' => $node->id]) }}"
                                class="btn-edit">
                                + Tambah Turunan
                            </a>
                        @endif

                        @if(!$hasChildren)
                            <form
                                action="{{ route('admin.gender.destroy', $node->id) }}"
                                method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-delete">
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </div>

                </div>

                @if($hasChildren)
                    <div class="gender-tree-children">
                        @include('admin.gender.partials.tree', [
                            'nodes' => $node->children
                        ])
                    </div>
                @endif
            </article>
        @endforeach
    </div>
@endif
