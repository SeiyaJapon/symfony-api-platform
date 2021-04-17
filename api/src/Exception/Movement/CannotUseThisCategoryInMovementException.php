<?php

declare(strict_types=1);

namespace App\Exception\Movement;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CannotUseThisCategoryInMovementException extends AccessDeniedHttpException
{
    public const MESSAGE = 'You can not use this category in this  movement';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}