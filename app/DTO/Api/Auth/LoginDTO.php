<?php

namespace app\DTO\Api\Auth;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class LoginDTO extends Data
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    )
    {
    }


    public static function rules(ValidationContext $context): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8',
        ];
    }

}
