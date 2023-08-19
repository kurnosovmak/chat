<?php

namespace App\DTO\Api\Messenger;

use App\Domain\Messenger\Core\Entities\Range;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class CreateChatDTO extends Data
{
    public function __construct(
        public readonly int $user_id,
    )
    {
    }


    public static function rules(ValidationContext $context): array
    {
        return [
            'user_id' => 'required|int|min:' . Range::MIN_USER_ID . '|max:' . Range::MAX_USER_ID,
        ];
    }

}
