<?php

declare(strict_types=1);

namespace FondBot\Chat\Commands;

use Clue\React\Stdio\Stdio;
use Illuminate\Http\Request;
use React\EventLoop\Factory;
use FondBot\Channels\Channel;
use FondBot\Foundation\Kernel;
use Illuminate\Console\Command;
use FondBot\Channels\ChannelManager;
use FondBot\Chat\Drivers\ConsoleDriver;
use Illuminate\Contracts\Events\Dispatcher;

class Tinker extends Command
{
    /** {@inheritdoc} */
    protected $signature = 'chat';

    /** {@inheritdoc} */
    protected $description = 'Gives try your chatbot in your local terminal.';

    /** {@inheritdoc} */
    public function handle()
    {
        app()->singleton('loop', function () {
            return Factory::create();
        });

        app()->singleton(Stdio::class, function () {
            $loop = resolve('loop');

            return new Stdio($loop);
        });

        $loop = resolve('loop');
        $stdio = resolve(Stdio::class);
        $kernel = resolve(Kernel::class);

        /** @var ChannelManager $channelManager */
        $channelManager = resolve(ChannelManager::class);
        $channelManager->extend('console', function () {
            return new ConsoleDriver;
        });

        $channelManager->register([
            'console' => [
                'driver' => 'console',
            ],
        ]);

        /** @var Channel $chanel */
        $chanel = $channelManager->create('console');

        $kernel->initialize($chanel);

        /** @var ConsoleDriver $driver */
        $driver = $kernel->getChannel()->getDriver();

        /** @var Dispatcher $events */
        $events = resolve('events');

        $stdio->getReadline()->setPrompt('You: ');
        $stdio->on('data', function ($message) use ($events, $driver) {
            $request = new Request;

            $request->replace([
                'message' => rtrim($message, "\r\n"),
            ]);

            $events->dispatch(
                $driver->createEvent($request)
            );
        });

        $loop->run();
    }
}
