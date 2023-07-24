<?php

namespace app\DTO\Api\Auth;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class RefreshDTO extends Data
{
    public function __construct(
        public readonly string $refresh_token,
    )
    {
    }


    public static function rules(ValidationContext $context): array
    {
        return [
            'refresh_token' => 'required|string',
        ];
    }

}
