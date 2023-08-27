<?php

namespace App\DTO\Api\Profile;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class SearchByTextDTO extends Data
{
    public function __construct(
        public readonly string $text,
    )
    {
    }


    public static function rules(ValidationContext $context): array
    {
        return [
            'text' => 'required|string|min:1',
        ];
    }

}
