<?php

declare(strict_types=1);

namespace App\Exception\Movement;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CannotCreateMovementForAnotherGroupException extends AccessDeniedHttpException
{
    public const MESSAGE = 'You can not create movement for another group';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}