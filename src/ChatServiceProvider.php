<?php

declare(strict_types=1);

namespace FondBot\Chat;

use Illuminate\Support\ServiceProvider;

class ChatServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\ChatCommand::class,
            ]);
        }
    }
}
