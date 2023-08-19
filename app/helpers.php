<?php


if (!function_exists('userId')) {
    /**
     * Возращает global user id авторизованного пользователя
     *
     * @return int
     */
    function userId(): int
    {
        return Auth::id() !== null ? Auth::id() + \App\Domain\Messenger\Core\Entities\Range::MIN_USER_ID : 0;
    }
}

