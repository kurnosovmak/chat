<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\DTO\Api\Auth\RegisterDTO;
use App\Models\User;


final class RegisterService
{
    public function register(RegisterDTO $registerDTO): void
    {
        $user = new User( [
            'name' => $registerDTO->name,
            'family' => $registerDTO->family,
            'patronymic' => $registerDTO->patronymic,
            'email' => $registerDTO->email,
            'password' => $registerDTO->password,
        ]);
        $user->save();
    }


}
