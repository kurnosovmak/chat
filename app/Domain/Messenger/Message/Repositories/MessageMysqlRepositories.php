<?php

namespace App\Domain\Messenger\Message\Repositories;

use App\Domain\Messenger\Chat\DTO\Chat\CreateChatQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatByChatIdQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByChatIdsQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByUserIdQuery;
use App\Domain\Messenger\Chat\DTO\Chat\FindChatsByUserIdsQuery;
use App\Domain\Messenger\Chat\Repositories\Contracts\ChatRepository;
use App\Domain\Messenger\Core\Entities\ChatId;
use App\Domain\Messenger\Core\Entities\ChatInfo;
use App\Domain\Messenger\Core\Entities\MessageId;
use App\Domain\Messenger\Core\Entities\MessageInfo;
use App\Domain\Messenger\Core\Entities\PeerId;
use App\Domain\Messenger\Core\Entities\UserId;
use App\Domain\Messenger\Core\Errors\ErrorData;
use App\Domain\Messenger\Message\DTO\Repository\GetMessagesByChatId;
use App\Domain\Messenger\Message\DTO\Repository\SendMessage;
use App\Domain\Messenger\Message\Repositories\Contracts\MessageRepository;
use App\Models\ChatBase;
use App\Models\ChatSub\Chat;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class MessageMysqlRepositories implements MessageRepository
{
    public static function create(): MessageMysqlRepositories
    {
        return new self(new Message());
    }

    private function __construct(private readonly Message $message)
    {
    }

    /**
     * @param GetMessagesByChatId $getMessagesByChatId
     * @return MessageId[]
     */
    public function getMessagesByChatId(GetMessagesByChatId $getMessagesByChatId): array
    {
        [$chatLocalId, $error] = $getMessagesByChatId->chatId->getLocalId();
        if ($error !== null) {
            return [null, $error];
        }

        $messages = $this->message->newQuery()
            ->where('chat_id', $chatLocalId)
            ->where('local_id', '>=', $getMessagesByChatId->startMessageId->getId())
            ->limit($getMessagesByChatId->per)
            ->get(['id']);

        $messagesIds = [];
        foreach ($messages as $message) {
            [$messageId, $error] = MessageId::create($message->id);
            if ($error !== null) {
                return [null, $error];
            }
            $messagesIds[] = $messageId;
        }
        return [$messagesIds, null];
    }

    /**
     * @param MessageId[] $messagesIds
     * @return array [?MessageInfo[], ?ErrorData]
     */
    public function getMessageByIds(array $messagesIds): array
    {
        $ids = array_map(fn(MessageId $messageId) => $messageId->getId(), $messagesIds);
        $messages = $this->message->newQuery()->findMany($ids);

        $messageInfos = [];
        foreach ($messages as $message) {
            /** @var MessageId $messageId */
            [$messageId, $error] = MessageId::create($message->id);
            if ($error !== null) {
                return [null, $error];
            }
            [$chatId, $error] = ChatId::localId($message->chat_id);
            if ($error !== null) {
                return [null, $error];
            }

            [$userId, $error] = UserId::localId($message->user_id);
            if ($error !== null) {
                return [null, $error];
            }

            [$messageInfo, $error] = MessageInfo::create(
                $chatId,
                $messageId,
                $message->local_id,
                $userId,
                $message->body,
                $message->is_read,
                $message->created_at,
                $message->updated_at,
            );
            if ($error !== null) {
                return [null, $error];
            }
            $messageInfos[$messageId->getId()] = $messageInfo;
        }
        return [$messageInfos, null];
    }

    /**
     * @param SendMessage $sendMessage
     * @return array [?MessageId, ?ErrorData]
     */
    public function sendMessage(SendMessage $sendMessage): array
    {
        [$chatLocalId, $error] = $sendMessage->chatId->getLocalId();
        if ($error !== null) {
            return [null, $error];
        }
        [$userLocalId, $error] = $sendMessage->userId->getLocalId();
        if ($error !== null) {
            return [null, $error];
        }

        /** @var Message $message */
        $message = $this->message->newQuery()->create([
            'chat_id' => $chatLocalId,
            'user_id' => $userLocalId,
            'local_id' => $this->getMaxLocalIdByChatLocalId($chatLocalId) + 1,
            'body' => $sendMessage->body,
        ]);
        return MessageId::create($message->id);
    }

    private function getMaxLocalIdByChatLocalId(int $chatLocalId): int
    {
        $message = $this->message->newQuery()
            ->where('chat_id', $chatLocalId)->selectRaw('max(local_id) as max_local_id')->first();
        return $message->max_local_id ?? 0;
    }
}
