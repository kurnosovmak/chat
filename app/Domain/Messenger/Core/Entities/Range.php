<?php

declare(strict_types=1);

namespace App\Domain\Messenger\Core\Entities;

final class Range
{
    const MIN_CHAT_ID = -1_000_000_00;
    const MAX_CHAT_ID = -1;
    const MIN_USER_ID = 1;
    const MAX_USER_ID = 200_000_000;

    const MIN_GROUP_ID = 200_000_001;
    const MAX_GROUP_ID = 400_000_000;
    const MIN_CHANNEL_ID = 400_000_001;
    const MAX_CHANNEL_ID = 600_000_000;

    public static function isUser(int $id): bool {
        return self::MIN_USER_ID <= $id && $id <= self::MAX_USER_ID;
    }


    public static function isChat(int $id): bool {
        return self::MIN_CHAT_ID <= $id && $id <= self::MAX_CHAT_ID;
    }

    public static function isChannel(int $id): bool {
        return self::MIN_CHANNEL_ID <= $id && $id <= self::MAX_CHANNEL_ID;
    }

    public static function isGroup(int $id): bool {
        return self::MIN_GROUP_ID <= $id && $id <= self::MAX_GROUP_ID;
    }
}
