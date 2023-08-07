<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\DTO\Api\Auth\RegisterDTO;
use App\Events\UserRegister;
use App\Services\Auth\RegisterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RegisterController
{

    public function __construct(
        protected readonly RegisterService $register,
    )
    {
    }

    public function register(RegisterDTO $registerDTO): JsonResponse
    {
        $user = $this->register->register($registerDTO);
        event(new UserRegister($user));
        return response()->json([
            'data' => [
                'id' => $user->id,
            ],
        ], Response::HTTP_OK);
    }

    public function resendCode(int $userId): JsonResponse
    {
        $this->register->sendCode($userId);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function verify(string $key): JsonResponse
    {
        $code = (int)substr($key, 0, 6);
        $id = (int)substr($key, 6);
        if (!$this->register->verify($id, $code)) {
            return response()->json([
                'message' => 'Invalid code'
            ], Response::HTTP_BAD_REQUEST);
        }
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

}
