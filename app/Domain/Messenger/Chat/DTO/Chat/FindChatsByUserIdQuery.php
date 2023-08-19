<?php

declare(strict_types=1);

namespace App\Domain\Messenger\Chat\DTO\Chat;

use App\Domain\Messenger\Core\Entities\UserId;

class FindChatsByUserIdQuery
{
    /**
     * @param UserId $userId
     */
    public function __construct(
        public readonly UserId $userId,
    )
    {
    }
}
