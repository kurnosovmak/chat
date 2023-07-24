<?php

namespace app\Services\Auth\DTO;

use Spatie\LaravelData\Data;

class TokenDTO extends Data
{
    public function __construct(
        public readonly string $accessToken,
        public readonly string $refreshToken,
        public readonly int    $expiresIn,
        public readonly string $tokenType,
    )
    {
    }

    public static function from(...$payloads): static
    {
        return new static(
            accessToken: $payloads[0]['access_token'],
            refreshToken: $payloads[0]['refresh_token'],
            expiresIn: $payloads[0]['expires_in'],
            tokenType: $payloads[0]['token_type'],
        );
    }
}
