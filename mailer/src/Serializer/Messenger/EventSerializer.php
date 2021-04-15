<?php

declare(strict_types=1);

namespace Mailer\Serializer\Messenger;

use Mailer\Messenger\Message\GroupRequestMessage;
use Mailer\Messenger\Message\RequestResetPasswordMessage;
use Mailer\Messenger\Message\UserRegisteredMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\Serializer;

class EventSerializer extends Serializer
{
    private const MAP_ORIGIN = 'App\Messenger\Message\UserRegisteredMessage';
    private const MAP_RESET_PASSWORD = 'App\Messenger\Message\RequestResetPasswordMessage';
    private const MAP_GROUP_REQUEST = 'App\Messenger\Message\GroupRequestMessage';

    public function decode(array $encodedEnvelope) : Envelope
    {
        $translatedType = $this->translateType($encodedEnvelope['headers']['type']);

        $encodedEnvelope['headers']['type'] = $translatedType;

        return parent::decode($encodedEnvelope);
    }

    private function translateType(string $type) : string
    {
        $map = [
            self::MAP_ORIGIN => UserRegisteredMessage::class,
            self::MAP_RESET_PASSWORD => RequestResetPasswordMessage::class,
            self::MAP_GROUP_REQUEST => GroupRequestMessage::class,
        ];

        return (array_key_exists($type, $map)) ? $map[$type] : $type;
    }
}