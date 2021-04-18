<?php

declare(strict_types=1);

namespace App\Exception\Movement;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class MovementDoesNotBelongToUserException extends AccessDeniedHttpException
{
    public const MESSAGE = 'This movement does not belong to this user';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}