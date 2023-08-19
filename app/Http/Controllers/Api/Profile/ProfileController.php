<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Entities\ProfileResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

final class ProfileController extends Controller
{
    public function me(): JsonResource
    {
        return new ProfileResource((Auth::user()));
    }
}
