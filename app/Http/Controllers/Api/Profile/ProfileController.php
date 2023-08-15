<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile;

use App\DTO\Api\Profile\SearchByTextDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\Entities\ProfileResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

final class ProfileController extends Controller
{
    public function me(): JsonResource
    {
        return new ProfileResource((Auth::user()));
    }

    public function updateMe(Request $request): JsonResource
    {
        $validatedData = $request->validate([
            'name' => ['required','string','max:255'],
            'family' => ['required', 'string','max:255'],
            'patronymic'=> ['string','max:255'],
        ]);
        Auth::user()->update($validatedData);

        return new ProfileResource((Auth::user()));
    }



    public function searchByText(SearchByTextDTO $searchByTextDTO): JsonResource
    {
        $profiles = User::verified()->search($searchByTextDTO->text)->limit(10)->get();
        return ProfileResource::collection($profiles);
    }
}
