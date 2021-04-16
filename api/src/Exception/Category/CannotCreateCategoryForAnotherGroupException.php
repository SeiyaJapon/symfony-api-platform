<?php

declare(strict_types=1);

namespace App\Exception\Category;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CannotCreateCategoryForAnotherGroupException extends AccessDeniedHttpException
{
    public const MESSAGE = 'You can not create category for another group';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}