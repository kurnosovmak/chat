<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModelImages extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_id',
        'image_id',
        ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function Images(): BelongsTo{
        return $this->belongsTo(Images::class);
    }
}
