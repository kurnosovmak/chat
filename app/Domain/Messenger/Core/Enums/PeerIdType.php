<?php

declare(strict_types=1);

namespace App\Domain\Messenger\Core\Enums;

enum PeerIdType
{
    case UserType;
    case ChatType;
    case ChannelType;
    case GroupType;
}
