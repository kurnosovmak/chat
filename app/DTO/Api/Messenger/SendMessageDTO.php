<?php

namespace App\DTO\Api\Messenger;

use App\Domain\Messenger\Core\Entities\Range;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class SendMessageDTO extends Data
{
    public function __construct(
        public readonly int    $chat_id,
        public readonly string $body,
    )
    {
    }


    public static function rules(ValidationContext $context): array
    {
        return [
            'chat_id' => 'required|int|min:' . Range::MIN_CHAT_ID . '|max:' . Range::MAX_CHAT_ID,
            'body' => 'required|string|min:1',
        ];
    }

}
