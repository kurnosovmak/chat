<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Services\Auth\DTO\TokenDTO;
use http\Exception\RuntimeException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Psy\Exception\ThrowUpException;

final class PassportAuth implements AuthContract
{

    public const TIMEOUT = 3;

    public function login(string $login, string $password): TokenDTO
    {
        $response = Http::asForm()->asJson()->timeout(self::TIMEOUT)->post($this->getAppUrl() . '/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'username' => $login,
            'password' => $password,
            'scope' => '',
        ]);
        if ($response->status() !== 200)
        {
            throw new AuthenticationException($response->json()['message']);
        }
        return TokenDTO::from($response->json());
    }

    public function refresh(string $refreshToken): TokenDTO
    {
        $response = Http::asForm()->asJson()->timeout(self::TIMEOUT)->post($this->getAppUrl() . '/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'scope' => '',
        ]);
        if ($response->status() !== 200)
        {
            throw new AuthenticationException($response->json()['message']);
        }

        return TokenDTO::from($response->json());
    }

    private function getAppUrl(): string
    {
        $id = config('app.url', null);
        if ($id === null) {
            throw new \Exception('Personal access client ID is not defined');
        }
        return $id;
    }
    private function getClientId(): string
    {
        $id = config('passport.personal_grant_client.id', null);
        if ($id === null) {
            throw new \Exception('Personal access client ID is not defined');
        }
        return $id;
    }

    private function getClientSecret(): string
    {
        $secret = config('passport.personal_grant_client.secret', null);
        if ($secret === null) {
            throw new \Exception('Personal access client secret is not defined');
        }
        return $secret;
    }

    public function logout(): void
    {
        Auth::user()->token()->revoke();
    }
}
