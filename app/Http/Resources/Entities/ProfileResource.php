<?php

declare(strict_types=1);

namespace App\Http\Resources\Entities;

use App\Enums\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

final class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'family' => $this->family,
            'patronymic' => $this->patronymic,
            'email' => $this->email,
            'roles' => $this->when(!$this->resource instanceof Collection, $this->getRoles())
        ];
    }

    private function getRoles(): array
    {
        /** @var \Illuminate\Database\Eloquent\Collection $roleSlugs */
        $roleSlugs = $this->resource->getRoleNames();
        $roles = [];
        foreach ($roleSlugs as $roleSlug) {
            $roles[] = [
                'slug' => $roleSlug,
                'title' => RoleEnum::getRoleBySlug($roleSlug)->getTitle(),
            ];
        }
        return $roles;
    }
}
