<?php

declare(strict_types=1);

namespace App\Domain\Messenger\Core\Entities;

use App\Domain\Messenger\Core\Enums\PeerIdType;
use App\Domain\Messenger\Core\Errors\ErrorData;
use Carbon\Carbon;

class MessageInfo
{

    public static function create(
        ChatId    $chat_id,
        MessageId $messageId,
        int       $localId,
        UserId    $userId,
        string    $body,
        bool      $isRead,
        Carbon    $createdAt,
        Carbon    $updatedAt,
    ): array
    {
        return [new self(
            $chat_id,
            $messageId,
            $localId,
            $userId,
            $body,
            $isRead,
            $createdAt,
            $updatedAt,
        ), null];
    }

    private function __construct(
        public readonly ChatId    $chat_id,
        public readonly MessageId $id,
        public readonly int       $localId,
        public readonly UserId    $userId,
        public readonly string    $body,
        public readonly bool      $isRead,
        public readonly Carbon    $createdAt,
        public readonly Carbon    $updatedAt,
    )
    {
    }
}
