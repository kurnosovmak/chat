<?php

declare(strict_types=1);

namespace App\Domain\Messenger\Message\DTO;

use App\Domain\Messenger\Core\Entities\ChatId;
use App\Domain\Messenger\Core\Entities\MessageId;
use App\Domain\Messenger\Core\Entities\PeerId;
use App\Domain\Messenger\Core\Entities\UserId;
use App\Domain\Messenger\Core\Errors\ErrorData;
use App\Domain\Messenger\Message\DTO\Message\GetHistoryQuery;
use App\Domain\Messenger\Message\DTO\Message\SendMessageQuery;

class MessageServiceQueriesEngine
{
    public static function create(): MessageServiceQueriesEngine
    {
        return new self();
    }

    private function __construct()
    {
    }


    /**
     * @param int $chatIdRow
     * @param int $messageIdRow
     * @param int $perRow
     * @return array [?GetHistoryQuery, ?ErrorData]
     */
    public function generateGetHistoryQuery(int $chatIdRow, int $messageIdRow, int $perRow): array
    {
        if ($perRow <= 0) {
            return [null, ErrorData::create('Per должен быть строго больше 0.')];
        }

        $chatPeerId = PeerId::create($chatIdRow);
        [$chatId, $error] = ChatId::create($chatPeerId);
        if ($error !== null) {
            return [null, $error];
        }
        [$messageId, $error] = MessageId::create($messageIdRow);
        if ($error !== null) {
            return [null, $error];
        }

        return [new GetHistoryQuery($chatId, $messageId, $perRow), null];
    }

    /**
     * @param int $chatIdRow
     * @param int $userIdRow
     * @param string $body
     * @return array [?SendMessageQuery, ?ErrorData]
     */
    public function generateSendMessageQuery(int $chatIdRow, int $userIdRow, string $body): array
    {
        [$chatId, $error] = ChatId::create(PeerId::create($chatIdRow));
        if ($error !== null) {
            return [null, $error];
        }
        [$userId, $error] = UserId::create(PeerId::create($userIdRow));
        if ($error !== null) {
            return [null, $error];
        }

        return [new SendMessageQuery(
            $chatId,
            $userId,
            $body,
        ), null];
    }
}
