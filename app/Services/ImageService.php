<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Api\Profile\UpdateDTO;
use App\Models\Images;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class ImageService
{
    public function uploadImage($image): Images
    {
        $fileExtension = $image->getClientOriginalExtension();
        $name = str_replace("-", "", \Str::uuid()->toString());

        // Сохранить файл на сервере
        $image->storeAs('public/images', $name . '.' . $fileExtension);

        // Создать запись в таблице Images
        $newImage = new Images();
        $newImage->name = $name . '.' . $fileExtension;
        $newImage->save();

        return $newImage;
    }
    public function attachToUser($userId, $imageId): bool
    {
        /** @var User $user */
        $user = User::findOrFail($userId);
        $tmp_avatars = $user->avatar;
        $user->avatar()->detach();
        foreach ($tmp_avatars as $image) {
            /** @var Images $image */
            $image->delete();
        }
        $user->avatar()->attach($imageId);
        return true;
    }
}
