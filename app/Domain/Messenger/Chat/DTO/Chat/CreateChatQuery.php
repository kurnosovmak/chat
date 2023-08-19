<?php

namespace App\Domain\Messenger\Chat\DTO\Chat;

use App\Domain\Messenger\Core\Entities\UserId;

class CreateChatQuery
{
    public function __construct(
        public readonly UserId $firstUserId,
        public readonly UserId $secondUserId,
    )
    {
    }
}
