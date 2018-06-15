<?php

declare(strict_types=1);

namespace FondBot\Chat;

use Illuminate\Support\ServiceProvider;

class ChatServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            Commands\ChatCommand::class,
        ]);
    }
}
