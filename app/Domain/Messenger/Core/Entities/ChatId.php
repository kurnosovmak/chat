<?php

declare(strict_types=1);

namespace App\Domain\Messenger\Core\Entities;

use App\Domain\Messenger\Core\Enums\PeerIdType;
use App\Domain\Messenger\Core\Errors\ErrorData;

class ChatId
{
    public const TYPE = PeerIdType::ChatType;

    /**
     * @param PeerId $peerId
     * @return array [?ChatId, ?ErrorData]
     */
    public static function create(PeerId $peerId): array
    {
        if ($peerId->getType() !== self::TYPE) {
            return [null, ErrorData::create('Переданно не верное значение id. Id = ' . $peerId->getId())];
        }
        return [new self($peerId), null];
    }

    /**
     * @param int $id
     * @return array [?ChatId, ?ErrorData]
     */
    public static function localId(int $id): array
    {
        $peerId = PeerId::create($id + Range::MIN_CHAT_ID);

        return self::create($peerId);
    }

    private function __construct(private readonly PeerId $peerId)
    {
    }

    public function getPeerId(): PeerId
    {
        return $this->peerId;
    }

    /**
     * @return array [?int, ?ErrorData]
     */
    public function getLocalId(): array
    {
        return $this->peerId->getLocalId();
    }
}
