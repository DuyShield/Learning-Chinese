<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    protected $table = 'vocabularies';

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim($term ?? '');

        if ($term === '') {
            return $query;
        }

        $pattern = '%'.$term.'%';

        return $query->where(function (Builder $matches) use ($pattern) {
            $matches->where('hanzi', 'like', $pattern)
                ->orWhere('pinyin', 'like', $pattern)
                ->orWhere('meaning', 'like', $pattern)
                ->orWhere('example_hanzi', 'like', $pattern)
                ->orWhere('example_pinyin', 'like', $pattern)
                ->orWhere('example_meaning', 'like', $pattern);
        });
    }
}
