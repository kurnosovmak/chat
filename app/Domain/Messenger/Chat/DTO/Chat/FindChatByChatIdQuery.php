<?php

declare(strict_types=1);

namespace App\Domain\Messenger\Chat\DTO\Chat;

use App\Domain\Messenger\Core\Entities\ChatId;

class FindChatByChatIdQuery
{
    public function __construct(
        public readonly ChatId $chatId,
    )
    {
    }
}
