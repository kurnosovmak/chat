<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Messenger;

use App\Domain\Messenger\Core\Entities\ChatId;
use App\Domain\Messenger\Core\Entities\PeerId;
use App\Domain\Messenger\Core\Entities\UserId;
use App\DTO\Api\Messenger\CreateChatDTO;
use App\DTO\Api\Messenger\GetChatsDTO;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class MessengerController extends Controller
{
    public function createChat(CreateChatDTO $createChatDTO): JsonResponse
    {
        // @todo переделать когда появится домменная зона с пользователя
        /** @var UserId $userId */
        [$userId, $_] = UserId::create(PeerId::create($createChatDTO->user_id));
        [$userLocalId, $_] = $userId->getLocalId();
        User::verified()->findOrFail($userLocalId);

        // ищем возможно чат уже существует
        $chatId = $this->chatAdapter->findChatByUserIds($createChatDTO->user_id, userId());

        if ($chatId === null) {
            // Создаем чат
            $chatId = $this->chatAdapter->createChat($createChatDTO->user_id, userId());
        }

        // Получаем зависимости
        $conversations = $this->getConversations([$chatId->getPeerId()->getId()]);

        return response()->json([
            'data' => [$chatId->getPeerId()->getId()],
            ...$conversations,
        ]);
    }

    public function getChats(GetChatsDTO $getChatsDTO): JsonResponse
    {
        /** @var PeerId[] $ids */
        $ids = [];
        //Добавляем чаты
        $ids = array_merge($ids, array_map(fn(ChatId $chatId) => $chatId->getPeerId(), $this->chatAdapter->findChatsByUserId(userId())));


        $conversations = [];
        if ($getChatsDTO->is_conversations) {
            // Получаем зависимости
            $conversations = $this->getConversations($ids);
        }

        return response()->json([
            'data' => array_map(fn(PeerId $id) => $id->getId(), $ids),
            ...$conversations
        ]);
    }


}
