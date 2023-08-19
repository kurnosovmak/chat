<?php

namespace App\Domain\Messenger\Chat\Services;

use App\Domain\Messenger\Chat\DTO\Chat\CreateChatQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatByChatIdQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByChatIdsQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByUserIdQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByUserIdsQuery;
use App\Domain\Messenger\Chat\Repositories\Contracts\ChatRepository;
use App\Domain\Messenger\Core\Entities\ChatId;

class ChatService
{
    /**
     * @param ChatRepository $repository
     * @return array [?ChatService, ?ErrorData]
     */
    public static function create(ChatRepository $repository): array
    {
        return [new self($repository), null];
    }

    private function __construct(
        private readonly ChatRepository $repository,
    )
    {
    }

    /**
     * @param CreateChatQuery $createChatQuery
     * @return array [?ChatId, ?ErrorData]
     */
    public function createChat(CreateChatQuery $createChatQuery): array
    {
        return $this->repository->createChat($createChatQuery);
    }

    /**
     * @param FindChatByChatIdQuery $findChatByChatIdQuery
     * @return array [?(ChatInfo|null), ?ErrorData]
     */
    public function findChatByChatId(FindChatByChatIdQuery $findChatByChatIdQuery): array
    {
        return $this->repository->findChatByChatId($findChatByChatIdQuery);
    }

    /**
     * @param FindChatsByChatIdsQuery $findChatsByChatIdsQuery
     * @return array [?array<int, ChatInfo>, ?ErrorData]
     */
    public function findChatsByChatIds(FindChatsByChatIdsQuery $findChatsByChatIdsQuery): array
    {
        return $this->repository->findChatsByChatIds($findChatsByChatIdsQuery);
    }

    /**
     * @param FindChatsByUserIdsQuery $findChatsByUserIdsQuery
     * @return array [?(ChatId|null), ?ErrorData]
     */
    public function findChatByUserIds(FindChatsByUserIdsQuery $findChatsByUserIdsQuery): array
    {
        return $this->repository->findChatByUserIds($findChatsByUserIdsQuery);
    }

    /**
     * @param FindChatsByUserIdQuery $findChatsByUserIdQuery
     * @return array [?ChatId[], ?ErrorData]
     */
    public function findChatsByUserId(FindChatsByUserIdQuery $findChatsByUserIdQuery): array
    {
        return $this->repository->findChatsByUserId($findChatsByUserIdQuery);
    }
}
