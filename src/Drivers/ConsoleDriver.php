<?php

declare(strict_types=1);

namespace FondBot\Chat\Drivers;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use Clue\React\Stdio\Stdio;
use FondBot\Channels\Driver;
use FondBot\Contracts\Event;
use Illuminate\Http\Request;
use FondBot\Contracts\Template;
use FondBot\Templates\Attachment;
use FondBot\Events\MessageReceived;

class ConsoleDriver extends Driver
{
    /** {@inheritdoc} */
    public function getName(): string
    {
        return 'Console';
    }

    /** {@inheritdoc} */
    public function getDefaultParameters(): array
    {
        return [];
    }

    /** {@inheritdoc} */
    public function getClient()
    {
        return null;
    }

    /** {@inheritdoc} */
    public function createEvent(Request $request): Event
    {
        $chat = new Chat('test', 'Chat Title');
        $user = new User('test', 'UserName', 'UserName');

        return new MessageReceived($chat, $user, $request->get('message'));
    }

    /** {@inheritdoc} */
    public function sendMessage(Chat $chat, User $recipient, string $text, Template $template = null): void
    {
        resolve(Stdio::class)->write('Bot: '.$text."\r\n");
    }

    /** {@inheritdoc} */
    public function sendAttachment(Chat $chat, User $recipient, Attachment $attachment): void
    {
        //
    }

    /** {@inheritdoc} */
    public function sendRequest(Chat $chat, User $recipient, string $endpoint, array $parameters = []): void
    {
        //
    }
}
