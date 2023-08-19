<?php

namespace App\Domain\Messenger\Core\Errors;

final class ErrorData
{
    public static function create(string $message, mixed $data = []): self
    {
        return new self($message, $data);
    }

    private function __construct(
        public readonly string $message,
        public readonly mixed  $data,
    )
    {
    }
}
