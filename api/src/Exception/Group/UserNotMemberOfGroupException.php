<?php

declare(strict_types=1);

namespace App\Exception\Group;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserNotMemberOfGroupException extends AccessDeniedHttpException
{
    public const MESSAGE = 'This user is not member of this group';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}