<?php

declare(strict_types=1);

namespace App\Domain\Messenger\Chat\DTO\Chat;

use App\Domain\Messenger\Core\Entities\ChatId;

class FindChatsByChatIdsQuery
{
    /**
     * @param ChatId[] $chatIds
     */
    public function __construct(
        public readonly array $chatIds,
    )
    {
    }
}
