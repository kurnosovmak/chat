<?php

namespace App\Domain\Messenger\Message\Services;

use App\Domain\Messenger\Chat\DTO\Chat\CreateChatQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatByChatIdQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByChatIdsQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByUserIdQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByUserIdsQuery;
use App\Domain\Messenger\Chat\Repositories\Contracts\ChatRepository;
use App\Domain\Messenger\Core\Entities\ChatId;
use App\Domain\Messenger\Core\Entities\MessageId;
use App\Domain\Messenger\Message\DTO\Message\GetHistoryQuery;
use App\Domain\Messenger\Message\DTO\Message\SendMessageQuery;
use App\Domain\Messenger\Message\DTO\Repository\GetMessagesByChatId;
use App\Domain\Messenger\Message\DTO\Repository\SendMessage;
use App\Domain\Messenger\Message\Repositories\Contracts\MessageRepository;

class MessageService
{
    /**
     * @param MessageRepository $repository
     * @return array [?MessageService, ?ErrorData]
     */
    public static function create(MessageRepository $repository): array
    {
        return [new self($repository), null];
    }

    private function __construct(
        private readonly MessageRepository $repository,
    )
    {
    }

    /**
     * @param GetHistoryQuery $getHistoryQuery
     * @return array [?MessageInfo[], ?ErrorData]
     */
    public function getHistory(GetHistoryQuery $getHistoryQuery): array
    {
        $criteria = GetMessagesByChatId::create(
            $getHistoryQuery->chatId,
            $getHistoryQuery->startMessageId,
            $getHistoryQuery->per);

        /** @var MessageId[] $messagesIds */
        [$messagesIds, $error] = $this->repository->getMessagesByChatId($criteria);
        if ($error !== null) {
            return [null, $error];
        }

        return $this->repository->getMessageByIds($messagesIds);
    }

    /**
     * @param SendMessageQuery $getHistoryQuery
     * @return array [?MessageId, ?ErrorData]
     */
    public function sendMessage(SendMessageQuery $getHistoryQuery): array
    {
        $criteria = SendMessage::create(
            $getHistoryQuery->chatId,
            $getHistoryQuery->userId,
            $getHistoryQuery->body);

        /** @var MessageId $messagesId */
        [$messagesId, $error] = $this->repository->sendMessage($criteria);
        if ($error !== null) {
            return [null, $error];
        }

        return [$messagesId, null];
    }

}
