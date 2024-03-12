<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_id', 'author_id', 'liked'
    ];


    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
