<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Profile;

use App\Http\Resources\ProfileResource;
use Illuminate\Support\Facades\Auth;

final class ProfileController
{
    public function me()
    {
        return new ProfileResource((Auth::user()));
    }
}
