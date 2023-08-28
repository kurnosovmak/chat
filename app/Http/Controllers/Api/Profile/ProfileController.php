<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile;

use App\DTO\Api\Profile\SearchByTextDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\Entities\ProfileResource;
use App\Models\User;
use App\Services\Profile\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\DTO\Api\Profile\UpdateDTO;
use Illuminate\Support\Facades\Validator;
use RuntimeException;

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
        $id = Auth::id();
        $user = $this->update->update($id, $updateDTO);

        return response()->json(['data' => [
            'name' => $user->name,
            'family' => $user->family,
            'patronymic' => $user->patronymic],], Response::HTTP_OK);
    }

    public function uploadAvatar(Request $request)
    {
        if (!$request->hasFile('image')) {
            throw new RuntimeException('картинка не найдена');
        }
        $validator = Validator::make($request->all(), ['image' => 'mimes:jpeg,jpg,png,gif|required|max:20000']);

        if ($validator->fails()) {
            throw new RuntimeException('картинка не правильного типа');
        }
        $image = $request->file('image');
        $this->update->updateImage(Auth::id(), $image);
        return response()->json(['data' => [],], Response::HTTP_OK);
    }

    public function searchByText(SearchByTextDTO $searchByTextDTO): JsonResource
    {
        $profiles = User::verified()->search($searchByTextDTO->text)->limit(10)->get();
        return ProfileResource::collection($profiles);
    }
}
