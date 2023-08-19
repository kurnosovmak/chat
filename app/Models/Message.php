<?php

namespace App\Models;

use App\Models\ChatSub\Chat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
        'chat_id',
        'local_id',
    ];

    protected $fillable = [
        'chat_id',
        'user_id',
        'local_id',
        'body',
        'is_read',
    ];


    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'id', 'chat_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
