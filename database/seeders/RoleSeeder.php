<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    const ROLES = [RoleEnum::Admin, RoleEnum::User];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var RoleEnum $role */
        foreach (self::ROLES as $role) {
            Role::create([
                'name' => $role->getSlug(),
            ]);
        }
    }
}
