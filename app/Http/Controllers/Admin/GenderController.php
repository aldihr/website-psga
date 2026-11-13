<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GenderData;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class GenderController extends Controller
{
    private const SORT_OPTIONS = [
        'custom', 'name_asc', 'name_desc', 'total_desc',
        'male_desc', 'female_desc', 'latest', 'oldest',
    ];

    public function index(Request $request)
    {
        $sort = $request->query('sort', 'custom');
        if (!in_array($sort, self::SORT_OPTIONS, true)) {
            $sort = 'custom';
        }

        // Eager-load parent/children to support hierarchy of any depth.
        $allItems = GenderData::with(['parent', 'children'])->get()->keyBy('id');
        $university = $allItems->first(fn ($item) => $item->parent_id === null || $item->parent_id === 0);

        $topLevelChildren = $university
            ? $this->sortItems($allItems->filter(fn ($item) => (int) $item->parent_id === (int) $university->id), $sort)
            : collect();

        $selectedNode = null;
        $nodeChildren = collect();
        $summaryRows = collect();

        if ($university) {
            $nodeId = $request->query('node_id', $request->query('faculty_id'));
            if ($nodeId && isset($allItems[(int) $nodeId])) {
                $candidate = $allItems[(int) $nodeId];
                // Only allow selecting nodes that belong to the university tree.
                if ($this->belongsToRoot($candidate, $university, $allItems)) {
                    $selectedNode = $candidate;
                    $nodeChildren = $this->sortItems(
                        $allItems->filter(fn ($item) => (int) $item->parent_id === (int) $selectedNode->id),
                        $sort
                    );
                }
            }

            // Default: university + direct children only.
            // Selected node: show the COMPLETE path from university to the selected node,
            // then only the selected node's direct children. This preserves intermediate
            // ancestors (e.g. University > Faculty > Mahasiswa > Forsa).
            if ($selectedNode) {
                $path = $this->ancestorPath($selectedNode, $university, $allItems);
                foreach ($path as $pathNode) {
                    $summaryRows->push($pathNode);
                }

                foreach ($nodeChildren as $child) {
                    $summaryRows->push($child);
                }

                // Guard against accidental duplicate rows while preserving hierarchy order.
                $summaryRows = $summaryRows->unique(fn ($item) => (int) $item->id)->values();
            } else {
                $summaryRows->push($university);
                foreach ($topLevelChildren as $child) {
                    $summaryRows->push($child);
                }
            }
        }

        return view('admin.gender.index', compact(
            'allItems', 'university', 'topLevelChildren', 'selectedNode',
            'nodeChildren', 'summaryRows', 'sort'
        ));
    }

    private function sortItems(Collection $items, string $sort): Collection
    {
        $items = collect($items);
        $sorted = match ($sort) {
            'name_asc' => $items->sortBy(fn ($item) => mb_strtolower($item->name), SORT_NATURAL | SORT_FLAG_CASE),
            'name_desc' => $items->sortBy(fn ($item) => mb_strtolower($item->name), SORT_NATURAL | SORT_FLAG_CASE)->reverse(),
            'total_desc' => $items->sort(fn ($a, $b) => (($b->male_count + $b->female_count) <=> ($a->male_count + $a->female_count)) ?: ($a->sort_order <=> $b->sort_order)),
            'male_desc' => $items->sort(fn ($a, $b) => ($b->male_count <=> $a->male_count) ?: ($a->sort_order <=> $b->sort_order)),
            'female_desc' => $items->sort(fn ($a, $b) => ($b->female_count <=> $a->female_count) ?: ($a->sort_order <=> $b->sort_order)),
            'latest' => $items->sort(fn ($a, $b) => (($b->created_at?->timestamp ?? 0) <=> ($a->created_at?->timestamp ?? 0)) ?: ($b->id <=> $a->id)),
            'oldest' => $items->sort(fn ($a, $b) => (($a->created_at?->timestamp ?? 0) <=> ($b->created_at?->timestamp ?? 0)) ?: ($a->id <=> $b->id)),
            default => $items->sort(fn ($a, $b) => ($a->sort_order <=> $b->sort_order) ?: ($a->id <=> $b->id)),
        };

        return $sorted->values();
    }

    private function belongsToRoot(GenderData $node, GenderData $root, Collection $allItems): bool
    {
        $seen = [];
        $current = $node;
        while ($current) {
            if ((int) $current->id === (int) $root->id) {
                return true;
            }
            if (isset($seen[$current->id]) || !$current->parent_id) {
                return false;
            }
            $seen[$current->id] = true;
            $current = $allItems->get((int) $current->parent_id);
        }
        return false;
    }

    private function ancestorPath(GenderData $node, GenderData $root, Collection $allItems): Collection
    {
        $path = collect();
        $seen = [];
        $current = $node;
        while ($current && !isset($seen[$current->id])) {
            $seen[$current->id] = true;
            $path->prepend($current);
            if ((int) $current->id === (int) $root->id) {
                break;
            }
            $current = $current->parent_id ? $allItems->get((int) $current->parent_id) : null;
        }
        return $path;
    }

    public function create(Request $request)
    {
        $parents = $this->parentsWithHierarchyLabels();
        $parentId = $request->query('parent_id');
        $nextOrder = $this->nextSortOrder($parentId);
        return view('admin.gender.create', compact('parents', 'parentId', 'nextOrder'));
    }

    public function store(Request $request)
    {
        $hasUniversity = GenderData::where(function ($query) {
            $query->whereNull('parent_id')->orWhere('parent_id', 0);
        })->exists();
        $data = $request->validate([
            'name' => [$hasUniversity ? 'required' : 'nullable', 'string', 'max:255'],
            'parent_id' => [$hasUniversity ? 'required' : 'nullable', 'integer', 'exists:gender_data,id'],
            'male_count' => 'required|integer|min:0',
            'female_count' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_published' => 'required|boolean',
        ], [
            'parent_id.required' => 'Pilih Universitas atau data lain yang menjadi tempat data baru ini berada.',
        ]);

        if (!$hasUniversity) {
            $data['name'] = 'Universitas';
        } else {
            $this->rejectStandaloneUniversityName($data['name']);
            $this->rejectDuplicateCategoryName($data['name']);
        }

        $this->validateHierarchyCounts($data);
        $data['sort_order'] = $data['sort_order'] ?? $this->nextSortOrder($data['parent_id'] ?? null);
        GenderData::create($data);

        return redirect()->route('admin.gender.index')->with('success', 'Data gender berhasil ditambahkan.');
    }

    public function edit(GenderData $gender)
    {
        $blockedIds = array_merge([(int) $gender->id], $this->descendantIds($gender));
        $parents = $this->parentsWithHierarchyLabels((int) $gender->id)
            ->reject(fn ($item) => in_array((int) $item->id, $blockedIds, true))
            ->values();
        $parentId = $gender->parent_id;
        $parent = $gender->parent;
        return view('admin.gender.edit', compact('gender', 'parents', 'parentId', 'parent'));
    }

    public function update(Request $request, GenderData $gender)
    {
        $isUniversity = empty($gender->parent_id);
        $data = $request->validate([
            'name' => [$isUniversity ? 'nullable' : 'required', 'string', 'max:255'],
            'parent_id' => [
                $gender->parent_id ? 'required' : 'nullable',
                'integer',
                'exists:gender_data,id',
                Rule::notIn(array_merge($this->descendantIds($gender), [(int) $gender->id])),
            ],
            'male_count' => 'required|integer|min:0',
            'female_count' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_published' => 'required|boolean',
        ], [
            'parent_id.required' => 'Pilih data yang menaungi data ini.',
        ]);

        if (!$isUniversity) {
            $this->rejectStandaloneUniversityName($data['name']);
            $this->rejectDuplicateCategoryName($data['name'], (int) $gender->id);
        }

        if ($isUniversity) {
            $data['name'] = $gender->name;
            $data['parent_id'] = null;
        }

        $this->validateHierarchyCounts($data, $gender);
        $data['sort_order'] = $data['sort_order'] ?? $gender->sort_order ?? 0;
        $gender->update($data);

        return redirect()->route('admin.gender.index')->with('success', 'Data gender berhasil diperbarui.');
    }

    private function rejectStandaloneUniversityName(string $name): void
    {
        if (strtolower(trim($name)) === 'universitas') {
            throw ValidationException::withMessages([
                'name' => 'Nama "Universitas" sudah digunakan untuk data utama. Tambahkan keterangan, misalnya Universitas_Dosen.',
            ]);
        }
    }

    private function rejectDuplicateCategoryName(string $name, ?int $exceptId = null): void
    {
        $normalizedName = mb_strtolower(trim($name));
        $duplicateExists = GenderData::query()
            ->whereRaw('LOWER(TRIM(name)) = ?', [$normalizedName])
            ->when($exceptId, fn ($query) => $query->where('id', '<>', $exceptId))
            ->exists();

        if ($duplicateExists) {
            throw ValidationException::withMessages([
                'name' => 'Nama ini sudah digunakan. Silakan pilih nama lain.',
            ]);
        }
    }

    private function validateHierarchyCounts(array $data, ?GenderData $current = null): void
    {
        $parentId = array_key_exists('parent_id', $data)
            ? $data['parent_id']
            : $current?->parent_id;
        $maleCount = (int) $data['male_count'];
        $femaleCount = (int) $data['female_count'];
        $errors = [];

        if ($parentId) {
            $parent = GenderData::find($parentId);
            if ($parent) {
                $siblings = GenderData::where('parent_id', $parentId);
                if ($current) {
                    $siblings->where('id', '<>', $current->id);
                }

                $maleTotal = (int) (clone $siblings)->sum('male_count') + $maleCount;
                $femaleTotal = (int) (clone $siblings)->sum('female_count') + $femaleCount;

                if ($maleTotal > (int) $parent->male_count) {
                    $errors['male_count'][] = sprintf(
                        'Total laki-laki kategori di bawah %s adalah %s, melebihi jumlah induk %s.',
                        $parent->name,
                        number_format($maleTotal, 0, ',', '.'),
                        number_format((int) $parent->male_count, 0, ',', '.')
                    );
                }

                if ($femaleTotal > (int) $parent->female_count) {
                    $errors['female_count'][] = sprintf(
                        'Total perempuan kategori di bawah %s adalah %s, melebihi jumlah induk %s.',
                        $parent->name,
                        number_format($femaleTotal, 0, ',', '.'),
                        number_format((int) $parent->female_count, 0, ',', '.')
                    );
                }
            }
        }

        if ($current) {
            $childMaleTotal = (int) $current->children()->sum('male_count');
            $childFemaleTotal = (int) $current->children()->sum('female_count');

            if ($childMaleTotal > $maleCount) {
                $errors['male_count'][] = sprintf(
                    'Jumlah laki-laki tidak boleh kurang dari total kategori di bawah data ini (%s).',
                    number_format($childMaleTotal, 0, ',', '.')
                );
            }

            if ($childFemaleTotal > $femaleCount) {
                $errors['female_count'][] = sprintf(
                    'Jumlah perempuan tidak boleh kurang dari total kategori di bawah data ini (%s).',
                    number_format($childFemaleTotal, 0, ',', '.')
                );
            }
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }

    private function nextSortOrder($parentId): int
    {
        return ((int) GenderData::where('parent_id', $parentId)->max('sort_order')) + 1;
    }

    private function parentsWithHierarchyLabels(?int $excludeChildId = null): Collection
    {
        $items = GenderData::orderBy('sort_order')->orderBy('name')->get();
        $byId = $items->keyBy('id');
        $childrenTotals = GenderData::query()
            ->selectRaw('parent_id, SUM(male_count) as children_male_total, SUM(female_count) as children_female_total')
            ->whereNotNull('parent_id')
            ->when($excludeChildId, fn ($query) => $query->where('id', '<>', $excludeChildId))
            ->groupBy('parent_id')
            ->get()
            ->keyBy('parent_id');

        foreach ($items as $item) {
            $parts = [];
            $current = $item;
            $seen = [];
            while ($current && !isset($seen[$current->id])) {
                $seen[$current->id] = true;
                array_unshift($parts, $current->name);
                $current = $current->parent_id ? $byId->get((int) $current->parent_id) : null;
            }
            $item->hierarchy_label = implode(' / ', $parts);
            $item->parent_name = $item->parent_id
                ? optional($byId->get((int) $item->parent_id))->name
                : null;
            $totals = $childrenTotals->get($item->id);
            $item->available_male_count = max(0, (int) $item->male_count - (int) ($totals->children_male_total ?? 0));
            $item->available_female_count = max(0, (int) $item->female_count - (int) ($totals->children_female_total ?? 0));
        }
        return $items;
    }

    private function descendantIds(GenderData $node): array
    {
        $ids = [];
        $queue = [$node->id];
        while ($queue) {
            $parentId = array_shift($queue);
            $children = GenderData::where('parent_id', $parentId)->pluck('id')->all();
            foreach ($children as $childId) {
                if (!in_array((int) $childId, $ids, true)) {
                    $ids[] = (int) $childId;
                    $queue[] = (int) $childId;
                }
            }
        }
        return $ids;
    }

    public function destroy(GenderData $gender)
    {
        if (empty($gender->parent_id)) {
            return redirect()->route('admin.gender.index')->with('error', 'Data Universitas merupakan data utama dan tidak dapat dihapus.');
        }

        DB::transaction(function () use ($gender) {
            $levels = [];
            $frontier = [(int) $gender->id];
            $seen = [(int) $gender->id => true];

            while ($frontier !== []) {
                $children = GenderData::whereIn('parent_id', $frontier)
                    ->lockForUpdate()
                    ->pluck('id')
                    ->map(fn ($id) => (int) $id)
                    ->all();
                $children = array_values(array_filter($children, fn ($id) => !isset($seen[$id])));

                if ($children === []) {
                    break;
                }

                $levels[] = $children;
                foreach ($children as $childId) {
                    $seen[$childId] = true;
                }
                $frontier = $children;
            }

            foreach (array_reverse($levels) as $levelIds) {
                foreach (array_chunk($levelIds, 500) as $idChunk) {
                    GenderData::whereIn('id', $idChunk)->delete();
                }
            }

            $gender->delete();
        });

        return redirect()->route('admin.gender.index')->with('success', 'Data dan semua data di bawahnya berhasil dihapus.');
    }
}
