<?php

declare(strict_types=1);

namespace App\Enums;

use RuntimeException;

enum RoleEnum: string
{
    case Admin = 'admin';
    case User = 'user';

    public static function getRoleBySlug(string $roleSlug): RoleEnum {
        $roleSlug = trim($roleSlug);
        foreach (RoleEnum::cases() as $role) {
            if($roleSlug == $role->getSlug()){
                return $role;
            }
        }
        throw new RuntimeException('Error role with slug = ' . $roleSlug . ' not found');
    }
    public static function getRoleSlug(RoleEnum $roleEnum): string {
        return $roleEnum->value;
    }
    public static function getRoleTitle(RoleEnum $roleEnum): string {
        return match ($roleEnum) {
            RoleEnum::Admin => 'Администратор',
            RoleEnum::User => 'Пользователь',
            default => 'Неизвестный',
        };
    }

    public function getSlug(): string {
        return RoleEnum::getRoleSlug($this);
    }

    public function getTitle(): string {
        return RoleEnum::getRoleTitle($this);
    }
}
