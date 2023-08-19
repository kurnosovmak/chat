<?php

declare(strict_types=1);

namespace App\Domain\Messenger\Core\Entities;

use App\Domain\Messenger\Core\Enums\PeerIdType;
use App\Domain\Messenger\Core\Errors\ErrorData;

class MessageId
{

    public static function create(int $id): array
    {
        return [new self($id), null];
    }

    private function __construct(private readonly int $id)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
