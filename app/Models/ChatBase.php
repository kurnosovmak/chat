<?php

namespace App\Models;

use App\Domain\Messenger\Core\Entities\MessageId;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property Collection $messages
 */
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

    protected $appends = [
        'last_message'
    ];

    public function scopeType(Builder $query, int $type): void
    {
        $query->where('type', $type);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'chat_id', 'id');
    }

    public function lastMessage(): Attribute
    {
        $last = $this->messages->sortBy('created_at')->last();
        return Attribute::make(
            get: fn() => $last
        );
    }
}
