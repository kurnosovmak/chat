<?php

declare(strict_types=1);

namespace App\Domain\Messenger\Core\Entities;

use App\Domain\Messenger\Core\Enums\PeerIdType;
use App\Domain\Messenger\Core\Errors\ErrorData;

class PeerId
{
    private ?PeerIdType $type = null;

    public static function create(int $id): self
    {
        return new self($id);
    }

    protected function __construct(
        private int $id,
    )
    {
        $this->initType();
    }

    private function initType(): void
    {
        if (Range::isUser($this->id)) {
            $this->type = PeerIdType::UserType;
            return;
        }
        if (Range::isChannel($this->id)) {
            $this->type = PeerIdType::ChannelType;
            return;
        }
        if (Range::isChat($this->id)) {
            $this->type = PeerIdType::ChatType;
            return;
        }
        if (Range::isGroup($this->id)) {
            $this->type = PeerIdType::GroupType;
            return;
        }
    }


    /**
     * @return array[?int, ?ErrorData]
     */
    public function getLocalId(): array
    {
        return match ($this->type) {
            PeerIdType::UserType => [$this->id - Range::MIN_USER_ID, null],
            PeerIdType::GroupType => [$this->id - Range::MIN_GROUP_ID, null],
            PeerIdType::ChatType => [$this->id - Range::MIN_CHAT_ID, null],
            PeerIdType::ChannelType => [$this->id - Range::MIN_CHANNEL_ID, null],
            default => [null, ErrorData::create('Id не попадает ни в один из range')]
        };
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): ?PeerIdType
    {
        return $this->type;
    }
}
