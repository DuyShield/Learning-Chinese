<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $grammar_point_id
 * @property string $hanzi
 * @property string|null $pinyin
 * @property string $meaning
 * @property int $order_index
 */
class GrammarExample extends Model
{
    public function grammarPoint(): BelongsTo
    {
        return $this->belongsTo(GrammarPoint::class);
    }
}
