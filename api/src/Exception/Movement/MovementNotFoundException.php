<?php

declare(strict_types=1);

namespace App\Exception\Movement;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MovementNotFoundException extends NotFoundHttpException
{
    private const MESSAGE_ID = 'Movement with id %s not found';
    private const MESSAGE_FILEPATH = 'Movement with filePath %s not found';

    public static function fromId(string $id): self
    {
        throw new self(sprintf(self::MESSAGE_ID, $id));
    }

    public static function fromFilePath(string $id): self
    {
        throw new self(sprintf(self::MESSAGE_FILEPATH, $id));
    }
}