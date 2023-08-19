<?php

namespace App\DTO\Api\Messenger;

use App\Domain\Messenger\Core\Entities\Range;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class GetHistoryDTO extends Data
{
    public function __construct(
        public readonly int $chat_id,
        public readonly int $message_id,
        public readonly int $per = 10,
    )
    {
    }


    public static function rules(ValidationContext $context): array
    {
        return [
            'chat_id' => 'required|int|min:' . Range::MIN_CHAT_ID . '|max:' . Range::MAX_CHAT_ID,
            'message_id' => 'required|int|min:0',
            'per' => 'int|min:1|max:100',
        ];
    }

}
