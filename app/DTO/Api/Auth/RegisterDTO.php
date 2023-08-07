<?php

declare(strict_types=1);

namespace App\DTO\Api\Auth;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use App\Models\User;

class RegisterDTO extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $family,
        public readonly ?string $patronymic,
        public readonly string $email,
        public readonly string $password,
    )
    {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'name' => ['required','string','max:255'],
            'family' => ['required', 'string','max:255'],
            'patronymic', ['string','max:255'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8' ],
        ];
    }

}
