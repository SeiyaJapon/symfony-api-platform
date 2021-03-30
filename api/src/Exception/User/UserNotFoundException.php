<?php

declare(strict_types=1);

namespace App\Exception\User;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserNotFoundException extends NotFoundHttpException
{
    private const MESSAGE_EMAIL = 'User with email %s not found';
    private const MESSAGE_ID_TOKEN = 'User with id %s and token %s not found';
    private const MESSAGE_ID_RESET_PASSWORD_TOKEN = 'User with id %s and reset password token %s not found';
    private const MESSAGE_ID = 'User with id %s not found';

    public static function fromEmail(string $email) : self
    {
        throw new self(sprintf(self::MESSAGE_EMAIL, $email));
    }

    public static function fromUserIdAndToken(string $id, string $token) : self
    {
        throw new self(sprintf(self::MESSAGE_ID_TOKEN, $id, $token));
    }

    public static function fromUserIdAndResetPasswordToken(string $id, string $resetPasswordToken) : self
    {
        throw new self(sprintf(self::MESSAGE_ID_RESET_PASSWORD_TOKEN, $id, $resetPasswordToken));
    }

    public static function fromUserId(string $id) : self
    {
        throw new self(sprintf(self::MESSAGE_ID, $id));
    }
}