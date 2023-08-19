<?php

namespace App\Domain\Messenger\Message\DTO\Repository;

use App\Domain\Messenger\Core\Entities\ChatId;
use App\Domain\Messenger\Core\Entities\MessageId;
use App\Domain\Messenger\Core\Entities\UserId;
use App\Models\User;

class SendMessage
{
    public static function create(ChatId $chatId, UserId $userId, string $body): SendMessage
    {
        return new self($chatId, $userId, $body);
    }

    private function __construct(
        public readonly ChatId    $chatId,
        public readonly UserId $userId,
        public readonly string    $body,
    )
    {
    }
}
