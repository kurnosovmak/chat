<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile;

use App\DTO\Api\Messenger\SearchByTextDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\Entities\ProfileResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

final class ProfileController extends Controller
{
    public function me(): JsonResource
    {
        return new ProfileResource((Auth::user()));
    }

    public function searchByText(SearchByTextDTO $searchByTextDTO): JsonResource
    {
        $profiles = User::verified()->search($searchByTextDTO->text)->limit(10)->get();
        return ProfileResource::collection($profiles);
    }
}
