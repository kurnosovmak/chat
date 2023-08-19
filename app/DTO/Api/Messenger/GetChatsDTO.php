<?php

namespace App\DTO\Api\Messenger;

use App\Domain\Messenger\Core\Entities\Range;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class GetChatsDTO extends Data
{
    public function __construct(
        public readonly bool $is_conversations = false,
    )
    {
    }


    public static function rules(ValidationContext $context): array
    {
        return [
            'is_conversations' => 'boolean',
        ];
    }

}
