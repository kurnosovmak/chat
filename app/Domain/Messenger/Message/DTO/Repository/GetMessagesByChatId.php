<?php

namespace App\Domain\Messenger\Message\DTO\Repository;

use App\Domain\Messenger\Core\Entities\ChatId;
use App\Domain\Messenger\Core\Entities\MessageId;

class GetMessagesByChatId
{
    public static function create(ChatId $chatId, MessageId $startMessageId, int $per = 20): GetMessagesByChatId
    {
        return new self($chatId, $startMessageId, $per);
    }

    private function __construct(
        public readonly ChatId    $chatId,
        public readonly MessageId $startMessageId,
        public readonly int       $per,
    )
    {
    }
}
