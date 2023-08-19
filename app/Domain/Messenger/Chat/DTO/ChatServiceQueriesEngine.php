<?php

namespace App\Domain\Messenger\Chat\DTO;

use App\Domain\Messenger\Chat\DTO\Chat\CreateChatQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatByChatIdQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByChatIdsQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByUserIdQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByUserIdsQuery;
use App\Domain\Messenger\Core\Entities\ChatId;
use App\Domain\Messenger\Core\Entities\PeerId;
use App\Domain\Messenger\Core\Entities\UserId;

class ChatServiceQueriesEngine
{
    /**
     * @return array [?ChatServiceQueriesEngine, ?ErrorData]
     */
    public static function create(): array
    {
        return [new self(), null];
    }

    private function __construct()
    {
    }


    /**
     * @param int $firstUserIdRow
     * @param int $secondUserIdRow
     * @return array [?CreateChatQuery, ?ErrorData]
     */
    public function generateCreateChatQuery(int $firstUserIdRow, int $secondUserIdRow): array
    {

        $firstPeerId = PeerId::create($firstUserIdRow);
        [$firstUserId, $error] = UserId::create($firstPeerId);
        if ($error !== null) {
            return [null, $error];
        }

        $secondPeerId = PeerId::create($secondUserIdRow);
        [$secondUserId, $error] = UserId::create($secondPeerId);
        if ($error !== null) {
            return [null, $error];
        }

        return [new CreateChatQuery($firstUserId, $secondUserId), null];
    }

    /**
     * @param int $chatId
     * @return array [?FindChatByChatIdQuery, ?ErrorData]
     */
    public function generateFindChatByChatIdQuery(int $chatId): array
    {
        $peerId = PeerId::create($chatId);
        [$chatId, $error] = ChatId::create($peerId);
        if ($error !== null) {
            return [null, $error];
        }

        return [new FindChatByChatIdQuery($chatId), null];
    }

    /**
     * @param array<int> $chatIds
     * @return array [?FindChatsByChatIdsQuery, ?ErrorData]
     */
    public function generateFindChatsByChatIdsQuery(array $chatIds): array
    {
        $chatIdsType = [];
        foreach ($chatIds as $chatId) {
            $peerId = PeerId::create($chatId);
            [$chatId, $error] = ChatId::create($peerId);
            if ($error !== null) {
                return [null, $error];
            }
            $chatIdsType[] = $chatId;
        }

        return [new FindChatsByChatIdsQuery($chatIdsType), null];
    }

    /**
     * @param int $firstUserIdRow
     * @param int $secondUserIdRow
     * @return array [?FindChatsByUserIdsQuery, ?ErrorData]
     */
    public function generateFindChatsByUserIdsQuery(int $firstUserIdRow, int $secondUserIdRow): array
    {
        $firstPeerId = PeerId::create($firstUserIdRow);
        [$firstUserId, $error] = UserId::create($firstPeerId);
        if ($error !== null) {
            return [null, $error];
        }

        $secondPeerId = PeerId::create($secondUserIdRow);
        [$secondUserId, $error] = UserId::create($secondPeerId);
        if ($error !== null) {
            return [null, $error];
        }

        return [new FindChatsByUserIdsQuery($firstUserId, $secondUserId), null];
    }

    /**
     * @param int $userIdRow
     * @return array [?FindChatsByUserIdsQuery, ?ErrorData]
     */
    public function generateFindChatsByUserIdQuery(int $userIdRow): array
    {
        $peerId = PeerId::create($userIdRow);
        [$userId, $error] = UserId::create($peerId);
        if ($error !== null) {
            return [null, $error];
        }

        return [new FindChatsByUserIdQuery($userId), null];
    }
}
