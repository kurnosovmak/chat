<?php

declare(strict_types=1);

namespace App\Http\Resources\Entities;

use App\Domain\Messenger\Core\Entities\ChatInfo;
use App\Enums\RoleEnum;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

final class ChatResource implements Arrayable
{

    /**
     * @param ChatInfo[] $chatInfos
     * @return ChatResource
     */
    public static function create(array $chatInfos, bool $isSingle = false): self
    {
        return new self($chatInfos, $isSingle);
    }

    /**
     * @param ChatInfo[] $chatInfos
     */
    private function __construct(
        private readonly array $chatInfos,
        private readonly bool  $isSingle,
    )
    {
    }

    /**
     * @return array<int, array>
     */
    public function toArray(): array
    {
        if ($this->isSingle && count($this->chatInfos) > 0) {
            return $this->formatChatInfo($this->chatInfos[0]);
        }
        $result = [];
        foreach ($this->chatInfos as $chatInfo) {
            $result[$chatInfo->getChatId()->getPeerId()->getId()] = $this->formatChatInfo($chatInfo);
        }
        return $result;
    }

    private function formatChatInfo(ChatInfo $chatInfo): array
    {
        return [
            'id' => $chatInfo->getChatId()->getPeerId()->getId(),
            'last_message' => $chatInfo->getLastMessageId()->getId(),
            'first_user_id' => $chatInfo->getFirstUserId()->getPeerId()->getId(),
            'second_user_id' => $chatInfo->getSecondUserId()->getPeerId()->getId(),
            'created_at' => $chatInfo->getCreatedAt()->format('Y-m-d h:i:s'),
            'updated_at' => $chatInfo->getUpdatedAt()->format('Y-m-d h:i:s'),
        ];
    }
}
