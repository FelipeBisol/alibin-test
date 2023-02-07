<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'user_id', 'title'];
    public $timestamps = false;

    public function summaries(): HasMany
    {
        return $this->hasMany(Summary::class)->where('summary_id', '=', null);
    }

    public function allSummaries(): HasMany
    {
        return $this->hasMany(Summary::class);
    }
}
