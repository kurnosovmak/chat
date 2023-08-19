<?php

declare(strict_types=1);

namespace App\Http\Resources\Entities;

use App\Domain\Messenger\Core\Entities\ChatInfo;
use App\Domain\Messenger\Core\Entities\MessageInfo;
use App\Enums\RoleEnum;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

final class MessageResource implements Arrayable
{

    /**
     * @param MessageInfo[] $messageInfos
     * @return MessageResource
     */
    public static function create(array $messageInfos, bool $isSingle = false): self
    {
        return new self($messageInfos, $isSingle);
    }

    /**
     * @param MessageInfo[] $messageInfos
     */
    private function __construct(
        private readonly array $messageInfos,
        private readonly bool  $isSingle,
    )
    {
    }

    /**
     * @return array<int, array>
     */
    public function toArray(): array
    {
        if ($this->isSingle && count($this->messageInfos) > 0) {
            return $this->formatMessageInfo($this->messageInfos[0]);
        }
        $result = [];
        foreach ($this->messageInfos as $messageInfo) {
            $result[$messageInfo->id->getId()] = $this->formatMessageInfo($messageInfo);
        }
        return $result;
    }

    private function formatMessageInfo(MessageInfo $messageInfo): array
    {
        return [
            'id' => $messageInfo->id->getId(),
            'local_id' => $messageInfo->localId,
            'chat_id' => $messageInfo->chat_id->getPeerId()->getId(),
            'sender_user' => $messageInfo->userId->getPeerId()->getId(),
            'body' => $messageInfo->body,
            'is_read' => $messageInfo->isRead,
            'created_at' => $messageInfo->getCreatedAt()->format('Y-m-d h:i:s'),
            'updated_at' => $messageInfo->getUpdatedAt()->format('Y-m-d h:i:s'),
        ];
    }
}
