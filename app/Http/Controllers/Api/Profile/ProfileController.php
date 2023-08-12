<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Resources\ProfileResource;
use Illuminate\Support\Facades\Auth;

class ProfileController
{
    public function me()
    {
        return new ProfileResource((Auth::user()));
    }


}
