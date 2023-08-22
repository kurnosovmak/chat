<?php

declare(strict_types=1);

namespace App\Services\Profile;

use app\DTO\Api\Profile\UpdateDTO;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class ProfileService
{

    public function updateMe(UpdateDTO $updateDTO): User
    {
        /** @var User $user */

        Auth::user()->update([
            'name' => $updateDTO->name,
            'family' => $updateDTO->family,
            'patronymic' => $updateDTO->patronymic,
            ]);
        $user = Auth::user();
        return $user;

    }
}
