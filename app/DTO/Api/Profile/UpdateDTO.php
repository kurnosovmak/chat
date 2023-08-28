<?php

declare(strict_types=1);

namespace App\DTO\Api\Profile;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class UpdateDTO extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $family,
        public readonly ?string $patronymic,
    )
    {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'name' => ['required','string','max:255'],
            'family' => ['required', 'string','max:255'],
            'patronymic', ['string','max:255'],
        ];
    }

}
