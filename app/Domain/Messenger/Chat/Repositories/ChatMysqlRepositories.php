<?php

namespace App\Domain\Messenger\Chat\Repositories;

use App\Domain\Messenger\Chat\DTO\Chat\CreateChatQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatByChatIdQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByChatIdsQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByUserIdQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByUserIdsQuery;
use App\Domain\Messenger\Chat\Repositories\Contracts\ChatRepository;
use App\Domain\Messenger\Core\Entities\ChatId;
use App\Domain\Messenger\Core\Entities\ChatInfo;
use App\Domain\Messenger\Core\Entities\UserId;
use App\Domain\Messenger\Core\Errors\ErrorData;
use App\Models\ChatBase;
use App\Models\ChatSub\Chat;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class ChatMysqlRepositories implements ChatRepository
{
    /**
     * @return array [?ChatMysqlRepositories, ?ErrorData]
     */
    public static function create(): array
    {
        return [new self(new Chat()), null];
    }

    private function __construct(private readonly Chat $chat)
    {
    }

    /**
     * @param CreateChatQuery $createChatQuery
     * @return array [?ChatId, ?ErrorData]
     */
    public function createChat(CreateChatQuery $createChatQuery): array
    {
        [$firstLocalId, $error] = $createChatQuery->firstUserId->getLocalId();
        if ($error !== null) {
            return [null, $error];
        }
        [$secondLocalId, $error] = $createChatQuery->secondUserId->getLocalId();
        if ($error !== null) {
            return [null, $error];
        }
        $ids = [$firstLocalId, $secondLocalId];

        /** @var Chat $chatRow */
        $chatRow = $this->chat->getQueryBuilder()->create([
            'type' => ChatBase::CHAT_TYPE,
        ]);

        if ($chatRow === null) {
            return [null, ErrorData::create('Ошибка при создании чата')];
        }

        $chatRow->users()->attach($ids);

        return ChatId::localId($chatRow->id);
    }

    /**
     * @param FindChatByChatIdQuery $findChatByChatIdQuery
     * @return array [?ChatInfo, ?ErrorData]
     */
    public function findChatByChatId(FindChatByChatIdQuery $findChatByChatIdQuery): array
    {
        [$chatInfos, $error] = $this->findChatsByChatIds(new FindChatsByChatIdsQuery([$findChatByChatIdQuery->chatId]));
        if ($error !== null) {
            return [null, $error];
        }
        return [$chatInfos[$findChatByChatIdQuery->chatId->getPeerId()->getId()] ?? null, null];
    }

    /**
     * @param FindChatsByChatIdsQuery $findChatsByChatIdsQuery
     * @return array [?array<int, ChatInfo>, ?ErrorData]
     */
    public function findChatsByChatIds(FindChatsByChatIdsQuery $findChatsByChatIdsQuery): array
    {
        $localIds = [];
        foreach ($findChatsByChatIdsQuery->chatIds as $chatId) {
            [$localId, $error] = $chatId->getLocalId();
            if ($error !== null) {
                return [null, ErrorData::create('Ошибка при получении local id.', $chatId)];
            }
            $localIds[] = $localId;
        }

        $chatRows = $this->chat->getQueryBuilder()->findMany($localIds);

        $result = [];
        foreach ($chatRows as $chatRow) {
            [$chatId, $error] = ChatId::localId($chatRow->id);
            if ($error !== null) {
                return [null, $error];
            }

            [$firstUserId, $error] = UserId::localId((int)($chatRow->user_lower_id));
            if ($error !== null) {
                return [null, $error];
            }

            [$secondUserId, $error] = UserId::localId((int)($chatRow->user_bigger_id));
            if ($error !== null) {
                return [null, $error];
            }

            $chatInfo = ChatInfo::create()
                ->setChatId($chatId)
                ->setFirstUserId($firstUserId)
                ->setSecondUserId($secondUserId)
                ->setCreatedAt($chatRow->created_at)
                ->setUpdatedAt($chatRow->updated_at);

            $result[$chatId->getPeerId()->getId()] = $chatInfo;
        }
        return [$result, null];
    }

    /**
     * @param FindChatsByUserIdsQuery $findChatsByUserIdsQuery
     * @return array [?(ChatId|null), ?ErrorData]
     */
    public function findChatByUserIds(FindChatsByUserIdsQuery $findChatsByUserIdsQuery): array
    {

        [$minChatIdRow, $error] = $findChatsByUserIdsQuery->getMinUserId()->getLocalId();
        if ($error !== null) {
            return [null, $error];
        }
        [$maxChatIdRow, $error] = $findChatsByUserIdsQuery->getMaxUserId()->getLocalId();
        if ($error !== null) {
            return [null, $error];
        }

        /** @var Chat $chat */

        $chat = $this->chat->getQueryBuilder()->whereHas('users', function (Builder $builder) use ($minChatIdRow, $maxChatIdRow) {
            $builder->groupBy('chat_user.chat_id')
                ->havingRaw('COUNT(*) = 2')
                ->having(function ($q) use ($minChatIdRow, $maxChatIdRow) {
                    $q->having('chat_user.user_id', $maxChatIdRow);
                    $q->orHaving('chat_user.user_id', $minChatIdRow);
                });
        })->first(['id']);

        if ($chat === null) {
            return [null, null];
        }

        return ChatId::localId($chat->id);
    }

    /**
     * @param FindChatsByUserIdQuery $findChatsByUserIdQuery
     * @return array [?ChatId[], ?ErrorData]
     */
    public function findChatsByUserId(FindChatsByUserIdQuery $findChatsByUserIdQuery): array
    {
        [$localId, $error] = $findChatsByUserIdQuery->userId->getLocalId();
        if ($error !== null) {
            return [null, $error];
        }

        /** @var Collection<Chat> $chats */
        $chats = $this->chat->getQueryBuilder()->whereHas('users', function (Builder $builder) use ($localId) {
            $builder->where('user_id', $localId);
        })->get(['id']);

        $chatIds = [];
        foreach ($chats as $chat) {
            [$chatId, $error] = ChatId::localId($chat->id);
            if ($error !== null) {
                return [null, $error];
            }
            $chatIds[] = $chatId;
        }

        return [$chatIds, null];
    }
}
