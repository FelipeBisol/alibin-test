<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Summary extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'book_id', 'summary_id', 'title', 'page'];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function subSummaries(): HasMany
    {
        return $this->hasMany(Summary::class);
    }
}
