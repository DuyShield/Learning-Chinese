<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $grammar_point_id
 * @property string $status
 */
class UserGrammarProgress extends Model
{
    protected $table = 'user_grammar_progress';

    protected $fillable = [
        'user_id',
        'grammar_point_id',
        'status',
    ];

    public function grammarPoint(): BelongsTo
    {
        return $this->belongsTo(GrammarPoint::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
