<?php

namespace App\Domain\Messenger\Message\Repositories\Contracts;

use App\Domain\Messenger\Chat\DTO\Chat\CreateChatQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatByChatIdQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByChatIdsQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByUserIdQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByUserIdsQuery;
use App\Domain\Messenger\Core\Entities\ChatId;
use App\Domain\Messenger\Core\Entities\MessageId;
use App\Domain\Messenger\Core\Entities\MessageInfo;
use App\Domain\Messenger\Message\DTO\Repository\GetMessagesByChatId;
use App\Domain\Messenger\Message\DTO\Repository\SendMessage;

interface MessageRepository
{
    /**
     * @param GetMessagesByChatId $getMessagesByChatId
     * @return array [?MessageId[], ?ErrorData]
     */
    public function getMessagesByChatId(GetMessagesByChatId $getMessagesByChatId): array;

    /**
     * @param MessageId[] $messagesIds
     * @return array [?MessageInfo[], ?ErrorData]
     */
    public function getMessageByIds(array $messagesIds): array;

    /**
     * @param SendMessage $sendMessage
     * @return array [?MessageId, ?ErrorData]
     */
    public function sendMessage(SendMessage $sendMessage): array;
}
