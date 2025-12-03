<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Taxon extends Model
{
    protected $fillable = [
        'name',
        'rank',
        'parent_id',
        'user_id',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Taxon::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Taxon::class, 'parent_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}