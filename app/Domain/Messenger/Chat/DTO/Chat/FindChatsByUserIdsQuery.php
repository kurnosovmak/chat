<?php

declare(strict_types=1);

namespace App\Domain\Messenger\Chat\DTO\Chat;

use App\Domain\Messenger\Core\Entities\UserId;

class FindChatsByUserIdsQuery
{
    /**
     * @param UserId $firstUserId
     * @param UserId $secondUserId
     */
    public function __construct(
        public readonly UserId $firstUserId,
        public readonly UserId $secondUserId,
    )
    {
    }

    public function getMinUserId(): UserId
    {
        if ($this->firstUserId->getPeerId()->getId() < $this->secondUserId->getPeerId()->getId()) {
            return $this->firstUserId;
        }

        return $this->secondUserId;
    }

    public function getMaxUserId(): UserId
    {
        if ($this->firstUserId->getPeerId()->getId() > $this->secondUserId->getPeerId()->getId()) {
            return $this->firstUserId;
        }

        return $this->secondUserId;
    }
}
