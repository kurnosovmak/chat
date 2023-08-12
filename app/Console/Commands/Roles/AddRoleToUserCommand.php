<?php

declare(strict_types=1);

namespace App\Console\Commands\Roles;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Console\Command;

final class AddRoleToUserCommand extends Command
{
    const ROLE_SLUG_FIELD = 'role_slug';
    const USER_ID_FIELD = 'user_id';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:add-role {' . self::USER_ID_FIELD . '} {' . self::ROLE_SLUG_FIELD . '}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //search user
        $user = User::findOrFail($this->argument(self::USER_ID_FIELD));
        //search role by slug
        $role = RoleEnum::getRoleBySlug($this->argument(self::ROLE_SLUG_FIELD));
        //add role to user
        $user->assignRole($role->getSlug());
        $this->components->info('Role ' . $role->getSlug() . '(' . $role->getTitle() . ') add  to user with id = ' . $user->id);
    }
}
