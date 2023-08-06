<?php

namespace app\DTO\Api\Auth;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use App\Models\User;

class RegisterDTO extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $surname,
        public readonly string $thirdName,
        public readonly string $email,
        public readonly string $password,
    )
    {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'name' => ['required','string','max:255'],
            'surname' => ['required', 'string','max:255'],
            'thirdName', ['string','max:255'],
            'email' => ['required', 'email', 'unique:'.app(User::class)->getTable()],
            'password' => ['required', 'string', 'min:8' ],
        ];
    }

}
