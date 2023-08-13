<?php

declare(strict_types=1);

namespace App\Console\Commands\Users;

use App\Models\User;
use Illuminate\Console\Command;

final class SlugUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:slug-refresh';

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
        $users = User::all();
        foreach ($users as $user) {
            $user->generateSlug();
            $user->save();
        }

    }
}
