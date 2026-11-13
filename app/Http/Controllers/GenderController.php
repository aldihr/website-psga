<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GenderData;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class GenderController extends Controller
{
    private const SORT_OPTIONS = ['custom', 'name_asc', 'name_desc', 'total_desc', 'male_desc', 'female_desc', 'latest', 'oldest'];

    public function index(Request $request)
    {
        $sort = $request->query('sort', 'custom');
        if (!in_array($sort, self::SORT_OPTIONS, true)) $sort = 'custom';

        $allItems = GenderData::query()
            ->where('is_published', true)
            ->with([
                'parent',
                'children' => fn ($query) => $query->where('is_published', true),
            ])
            ->get()
            ->keyBy('id');
        $university = $allItems->first(fn ($item) => $item->parent_id === null || (int) $item->parent_id === 0);
        $topLevelChildren = $university
            ? $this->sortItems($allItems->filter(fn ($item) => (int) $item->parent_id === (int) $university->id), $sort)
            : collect();

        $selectedNode = null;
        $nodeChildren = collect();
        $summaryRows = collect();
        $ancestorPath = collect();

        if ($university) {
            $nodeId = $request->query('node_id');
            if ($nodeId && isset($allItems[(int) $nodeId])) {
                $candidate = $allItems[(int) $nodeId];
                if ($this->belongsToRoot($candidate, $university, $allItems)) {
                    $selectedNode = $candidate;
                    $ancestorPath = $this->ancestorPath($selectedNode, $university, $allItems);
                    $nodeChildren = $this->sortItems(
                        $allItems->filter(fn ($item) => (int) $item->parent_id === (int) $selectedNode->id), $sort
                    );
                }
            }

            if ($selectedNode) {
                foreach ($ancestorPath as $item) $summaryRows->push($item);
                foreach ($nodeChildren as $item) $summaryRows->push($item);
                $summaryRows = $summaryRows->unique(fn ($item) => (int) $item->id)->values();
            } else {
                $summaryRows->push($university);
                foreach ($topLevelChildren as $item) $summaryRows->push($item);
            }
        }

        return view('gender.index', compact(
            'university', 'topLevelChildren', 'selectedNode', 'nodeChildren',
            'summaryRows', 'ancestorPath', 'sort'
        ));
    }

    private function sortItems(Collection $items, string $sort): Collection
    {
        $items = collect($items);
        $sorted = match ($sort) {
            'name_asc' => $items->sortBy(fn ($i) => mb_strtolower($i->name), SORT_NATURAL | SORT_FLAG_CASE),
            'name_desc' => $items->sortBy(fn ($i) => mb_strtolower($i->name), SORT_NATURAL | SORT_FLAG_CASE)->reverse(),
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
        $seen = []; $current = $node;
        while ($current) {
            if ((int) $current->id === (int) $root->id) return true;
            if (isset($seen[$current->id]) || !$current->parent_id) return false;
            $seen[$current->id] = true;
            $current = $allItems->get((int) $current->parent_id);
        }
        return false;
    }

    private function ancestorPath(GenderData $node, GenderData $root, Collection $allItems): Collection
    {
        $path = collect(); $seen = []; $current = $node;
        while ($current && !isset($seen[$current->id])) {
            $seen[$current->id] = true;
            $path->prepend($current);
            if ((int) $current->id === (int) $root->id) break;
            $current = $current->parent_id ? $allItems->get((int) $current->parent_id) : null;
        }
        return $path;
    }
}
