<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\DTO\Api\Auth\RegisterDTO;
use App\Mail\RegisterCode;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Mail;


final class RegisterService
{
    /**
     * @throws AuthenticationException
     */
    public function register(RegisterDTO $registerDTO): User
    {
        /** @var User $user */
        $user = User::query()->where('email', $registerDTO->email)->first();

        if($user && $user->hasVerifiedEmail()){
            throw new AuthenticationException('User with this email is exist');
        }

        if($user && !$user->hasVerifiedEmail()){
            $user->forceDelete();
        }

        $user = User::create([
            'name' => $registerDTO->name,
            'family' => $registerDTO->family,
            'patronymic' => $registerDTO->patronymic,
            'email' => $registerDTO->email,
            'password' => $registerDTO->password,
        ]);

        return $user;
    }

    public function sendCode(int $userId): void
    {
        $user = User::query()->findOrFail($userId);

        if ($user->code === null || $user->attempts_count >= User::MAX_ATTEMPTS_COUNT) {
            $user->code = $this->generateCode();
            $user->attempts_count = 0;
            $user->save();
        }

        $this->emailSend($user->email, (string)$user->code);
    }

    /**
     * @throws AuthenticationException
     */
    public function verify(int $userId, int $code): bool
    {
        $user = User::query()->findOrFail($userId);
        if($user->hasVerifiedEmail()) {
            throw new AuthenticationException('User is verified');
        }
        if($user->attempts_count >= User::MAX_ATTEMPTS_COUNT) {
            throw new AuthenticationException('Code attempts count ended');
        }
        if($user->code !== $code) {
            $user->increment('attempts_count');
            return false;
        }
        $user->markEmailAsVerified();
        return true;
    }

    private function emailSend(string $email, string $code): void
    {
        Mail::to($email)->send(new RegisterCode($code));
    }

    private function generateCode(): int
    {
        return rand(100000, 999999);
    }

}
