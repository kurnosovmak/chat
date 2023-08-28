<?php

declare(strict_types=1);

namespace App\Services\Profile;

use app\DTO\Api\Profile\UpdateDTO;
use App\Models\Images;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;

final class ProfileService
{
    public function __construct(
        private readonly ImageService $imageService,
    )
    {
    }

    public function update($id, UpdateDTO $updateDTO): User
    {
        /** @var User $user */
        $user = User::find($id);

        $user->update([
            'name' => $updateDTO->name,
            'family' => $updateDTO->family,
            'patronymic' => $updateDTO->patronymic,
            ]);

        return $user;
    }

    public function updateImage($id, $image): bool
    {
        $imageModel = $this->imageService->uploadImage($image);
        return $this->imageService->attachToUser($id, $imageModel->id);
    }
}
