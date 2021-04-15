<?php

declare(strict_types=1);

namespace App\Exception\Group;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class OwnerCannotBeDeletedException extends ConflictHttpException
{
    public const MESSAGE = 'Owner can not be deleted from group. Try deleting group instead';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}