<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile;

use App\DTO\Api\Profile\SearchByTextDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\Entities\ProfileResource;
use App\Models\ModelImages;
use App\Services\Profile\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Images;
use Illuminate\Support\Facades\DB;
use App\DTO\Api\Profile\UpdateDTO;

final class ProfileController extends Controller
{
    public function __construct(
        protected readonly ProfileService $update,
    )
    {
    }

    public function me(): JsonResource
    {
        return new ProfileResource((Auth::user()));
    }

    public function updateMe(UpdateDTO $updateDTO): JsonResponse
    {
        $user = $this->update->updateMe($updateDTO);

        return response()->json(['data' => [
            'name' => $user->name,
            'family' => $user->family,
            'patronymic' => $user->patronymic],], Response::HTTP_OK);
    }

    public function uploadAvatar(Request $request)
    {
        // ДО СИХ ПОР РАЗБИРАЮСЬ С АВАТАРКАМИ, немного не понятно.. я запутался xD

        $user = Auth::user();

        // Проверить наличие загружаемой картинки
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $image->getClientOriginalExtension();

            // Сохранить файл на сервере
            $image->storeAs('avatar_images', $filename);

            // Создать запись в таблице Images
            $newImage = new Images();
            $newImage->image = $filename;
            $newImage->save();


    public function searchByText(SearchByTextDTO $searchByTextDTO): JsonResource
    {
        $profiles = User::verified()->search($searchByTextDTO->text)->limit(10)->get();
        return ProfileResource::collection($profiles);
    }
            // Проверить есть ли у пользователя аватарка
            $userExists = ModelImages::query()->where('model_id', $user->id)->exists();
            $ImageUser = new ModelImages();
            if ($userExists) {
                $ImageUser->avatar_images_id = $newImage->id;
                $ImageUser->update();
            } else {
                // Создать запись в таблице ModelImages
                $ImageUser->users_id = $user->id;
                $ImageUser->avatar_images_id = $newImage->id;
                $ImageUser->save();
            }
            return response()->json(['message' => 'Аватарка успешно добавлена']);
        }
    }
}
