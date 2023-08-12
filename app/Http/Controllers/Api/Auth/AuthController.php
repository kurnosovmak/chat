<?php

namespace App\Http\Controllers\Api\Auth;

use App\DTO\Api\Auth\LoginDTO;
use App\DTO\Api\Auth\RefreshDTO;
use App\Services\Auth\AuthContract;
use Illuminate\Http\JsonResponse;

final class AuthController
{
    public function __construct(
        protected readonly AuthContract $auth,
    )
    {
    }

    public function login(LoginDTO $loginDTO): JsonResponse
    {

        $token = $this->auth->login($loginDTO->email, $loginDTO->password);
        return response()->json([
           'data' => $token->toArray(),
        ]);
    }

    public function refresh(RefreshDTO $refreshDTO): JsonResponse
    {
        $token = $this->auth->refresh($refreshDTO->refresh_token);
        return response()->json([
            'data' => $token->toArray(),
        ]);
    }

    public function logout(): JsonResponse
    {
        $this->auth->logout();
        return response()->json(status: 204);
    }
}
