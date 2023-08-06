<?php

namespace App\Http\Controllers\Api\Auth;

use App\DTO\Api\Auth\RegisterDTO;
use App\DTO\Api\Auth\RefreshDTO;
use App\Services\Auth\RegisterService;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController
{

    public function __construct(
        protected readonly RegisterService $register,
    )
    {
    }

    public function register(RegisterDTO $registerDTO): JsonResponse
    {
        $this->register->register($registerDTO);
        return response()->json(status: 205);
    }

}
