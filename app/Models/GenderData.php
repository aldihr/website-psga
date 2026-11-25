<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GenderData extends Model
{
    protected $table = 'gender_data';

    protected $fillable = [
        'parent_id',
        'name',
        'male_count',
        'female_count',
        'description',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'parent_id' => 'integer',
        'male_count' => 'integer',
        'female_count' => 'integer',
        'sort_order' => 'integer',
        'is_published' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            GenderData::class,
            'parent_id'
        );
    }

    public function children(): HasMany
    {
        return $this->hasMany(
            GenderData::class,
            'parent_id'
        )->orderBy('sort_order')->orderBy('id');
    }

    public function getTotalAttribute(): int
    {
        return (int) $this->male_count + (int) $this->female_count;
    }

    public function getMalePercentageAttribute(): float
    {
        return $this->total > 0
            ? round(($this->male_count / $this->total) * 100, 2)
            : 0;
    }

    public function getFemalePercentageAttribute(): float
    {
        return $this->total > 0
            ? round(($this->female_count / $this->total) * 100, 2)
            : 0;
    }
}
