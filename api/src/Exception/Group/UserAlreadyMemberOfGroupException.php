<?php

declare(strict_types=1);

namespace App\Exception\Group;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class UserAlreadyMemberOfGroupException extends ConflictHttpException
{
    public const MESSAGE = 'This user is already member of this group';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}