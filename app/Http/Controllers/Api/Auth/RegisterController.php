<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\DTO\Api\Auth\RegisterDTO;
use App\Services\Auth\RegisterService;
use Illuminate\Http\JsonResponse;

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
