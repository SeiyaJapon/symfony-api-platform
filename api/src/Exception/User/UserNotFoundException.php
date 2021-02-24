<?php

declare(strict_types=1);

namespace App\Exception\User;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserNotFoundException extends NotFoundHttpException
{
    private const MESSAGE_EMAIL = 'User with email %s not found';
    private const MESSAGE_ID_TOKEN = 'User with id %s and token %s not found';

    public static function fromEmail(string $email) : self
    {
        throw new self(sprintf(self::MESSAGE_EMAIL, $email));
    }

    public static function fromUserIdAndToken(string $id, string $token) : self
    {
        throw new self(sprintf(self::MESSAGE_ID_TOKEN, $id, $token));
    }
}