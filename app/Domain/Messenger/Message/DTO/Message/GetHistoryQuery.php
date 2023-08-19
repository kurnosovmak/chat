<?php

namespace App\Domain\Messenger\Message\DTO\Message;

use App\Domain\Messenger\Core\Entities\ChatId;
use App\Domain\Messenger\Core\Entities\MessageId;
use App\Domain\Messenger\Core\Entities\UserId;

class GetHistoryQuery
{
    public function __construct(
        public readonly ChatId $chatId,
        public readonly MessageId $startMessageId,
        public readonly int $per,
    )
    {
    }
}
