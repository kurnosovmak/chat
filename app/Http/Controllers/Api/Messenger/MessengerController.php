<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Messenger;

use App\Domain\Messenger\Core\Entities\ChatId;
use App\Domain\Messenger\Core\Entities\MessageInfo;
use App\Domain\Messenger\Core\Entities\PeerId;
use App\Domain\Messenger\Core\Entities\UserId;
use App\DTO\Api\Messenger\CreateChatDTO;
use App\DTO\Api\Messenger\GetChatsDTO;
use App\DTO\Api\Messenger\GetHistoryDTO;
use App\DTO\Api\Messenger\SendMessageDTO;
use App\Http\Controllers\MessengerControllerBase;
use App\Http\Resources\Entities\MessageResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use RuntimeException;

final class MessengerController extends MessengerControllerBase
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

    public function getHistory(GetHistoryDTO $getHistoryDTO): JsonResponse
    {
        $messageInfos = $this->messageAdapter->getHistory(
            $getHistoryDTO->chat_id,
            $getHistoryDTO->message_id,
            $getHistoryDTO->per,
        );

        $conversationIds = [];
        foreach ($messageInfos as $messageInfo) {
            $conversationIds[$messageInfo->userId->getPeerId()->getId()] = 1;
        }

        $conversationIds = array_keys($conversationIds);

        $conversations = $this->getConversations($conversationIds);

        return response()->json([
            'data' => MessageResource::create($messageInfos)->toArray(),
            ...$conversations,
        ]);
    }

    public function sendMessage(SendMessageDTO $sendMessageDTO): JsonResponse
    {
        $chatId = $this->chatAdapter->findChatByChatId($sendMessageDTO->chat_id);
        if ($chatId === null) {
            throw new RuntimeException('Чат не найден');
        }
        $messageId = $this->messageAdapter->sendMessage(
            $sendMessageDTO->chat_id,
            userId(),
            $sendMessageDTO->body,
        );
        if ($messageId === null) {
            throw new RuntimeException('Ошибка при оправке сообщения');
        }

        return response()->json([
            'data' => [$messageId->getId()],
        ]);
    }

}
