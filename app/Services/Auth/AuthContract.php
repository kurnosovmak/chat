<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Services\Auth\DTO\TokenDTO;

interface AuthContract
{
    public function login(string $login, string $password): TokenDTO;
    public function refresh(string $refreshToken): TokenDTO;
    public function logout(): void;
}
