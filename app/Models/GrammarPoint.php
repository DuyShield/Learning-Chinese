<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $title
 * @property string|null $structure
 * @property string $explanation
 * @property string|null $hsk_level
 * @property int $order_index
 * @property-read \Illuminate\Database\Eloquent\Collection<int, GrammarExample> $examples
 * @property-read UserGrammarProgress|null $progress
 */
class GrammarPoint extends Model
{
    public function examples(): HasMany
    {
        return $this->hasMany(GrammarExample::class);
    }

    public function progress(): HasOne
    {
        return $this->hasOne(UserGrammarProgress::class);
    }
}
