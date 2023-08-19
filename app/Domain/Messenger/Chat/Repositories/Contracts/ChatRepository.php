<?php

namespace App\Domain\Messenger\Chat\Repositories\Contracts;

use App\Domain\Messenger\Chat\DTO\Chat\CreateChatQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatByChatIdQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByChatIdsQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByUserIdQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByUserIdsQuery;
use App\Domain\Messenger\Core\Entities\ChatId;

interface ChatRepository
{
    /**
     * @param CreateChatQuery $createChatQuery
     * @return array [?ChatId, ?ErrorData]
     */
    public function createChat(CreateChatQuery $createChatQuery): array;

    /**
     * @param FindChatByChatIdQuery $findChatByChatIdQuery
     * @return array [?(ChatInfo|null), ?ErrorData]
     */
    public function findChatByChatId(FindChatByChatIdQuery $findChatByChatIdQuery): array;

    /**
     * @param FindChatsByChatIdsQuery $findChatsByChatIdsQuery
     * @return array [?ChatInfo[], ?ErrorData]
     */
    public function findChatsByChatIds(FindChatsByChatIdsQuery $findChatsByChatIdsQuery): array;

    /**
     * @param FindChatsByUserIdsQuery $findChatsByUserIdsQuery
     * @return array [?(ChatId|null), ?ErrorData]
     */
    public function findChatByUserIds(FindChatsByUserIdsQuery $findChatsByUserIdsQuery): array;

    /**
     * @param FindChatsByUserIdQuery $findChatsByUserIdQuery
     * @return array [?ChatId[], ?ErrorData]
     */
    public function findChatsByUserId(FindChatsByUserIdQuery $findChatsByUserIdQuery): array;
}
