<?php

declare(strict_types=1);

namespace App\Exception\Group;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CannotCreateGroupForAnotherUserException extends AccessDeniedHttpException
{
    public const MESSAGE = 'You can not create groups for another user';

    public function __construct(string $message = null, \Throwable $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct(self::MESSAGE);
    }
}