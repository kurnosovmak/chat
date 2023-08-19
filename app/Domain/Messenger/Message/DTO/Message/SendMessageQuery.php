<?php

namespace App\Domain\Messenger\Message\DTO\Message;

use App\Domain\Messenger\Core\Entities\ChatId;
use App\Domain\Messenger\Core\Entities\MessageId;
use App\Domain\Messenger\Core\Entities\UserId;

class SendMessageQuery
{
    public function __construct(
        public readonly ChatId $chatId,
        public readonly UserId $userId,
        public readonly string $body,
    )
    {
    }
}
