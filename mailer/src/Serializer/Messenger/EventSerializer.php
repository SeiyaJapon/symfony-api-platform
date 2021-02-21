<?php

declare(strict_types=1);

namespace Mailer\Serializer\Messenger;

use Mailer\Messenger\Message\UserRegisteredMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\Serializer;

class EventSerializer extends Serializer
{
    private const MAP_ORIGIN = 'App\Messenger\Message\UserRegisteredMessage';

    public function decode(array $encodedEnvelope) : Envelope
    {
        $translatedType = $this->translateType($encodedEnvelope['headers']['type']);

        $encodedEnvelope['headers']['type'] = $translatedType;

        return parent::decode($encodedEnvelope);
    }

    private function translateType(string $type) : string
    {
        $map = [self::MAP_ORIGIN => UserRegisteredMessage::class];

        return (array_key_exists($type, $map)) ? $map[$type] : $type;
    }
}