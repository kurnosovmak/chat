<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

abstract class ChatBase extends Model
{
    use HasFactory;

    const CHAT_TYPE = 1;
    const GROUP_TYPE = 2;
    const CHANNEL_TYPE = 3;

    protected $guarded = [
        'id',
        'user_lower_id',
        'user_bigger_id',
    ];

    protected $fillable = [
        'type',
        'user_lower_id',
        'user_bigger_id',
    ];

    public function scopeType(Builder $query, int $type): void
    {
        $query->where('type', $type);
    }
}
