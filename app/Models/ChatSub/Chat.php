<?php

namespace App\Models\ChatSub;

use App\Models\ChatBase;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property Collection $users
 */
class Chat extends ChatBase
{

    protected $appends = [
        'user_lower_id',
        'user_bigger_id',
    ];

    public function getQueryBuilder(): Builder
    {
        return self::newQuery()->type(ChatBase::CHAT_TYPE);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_user');
    }

    public function userLowerId(): Attribute
    {
        $minUser = $this->users->sortBy('id')->first();

        return Attribute::make(
            get: fn() => $minUser->id,
        );
    }

    public function userBiggerId(): Attribute
    {
        $maxUser = $this->users->sortBy('id')->last();

        return Attribute::make(
            get: fn() => $maxUser->id,
        );
    }
}
