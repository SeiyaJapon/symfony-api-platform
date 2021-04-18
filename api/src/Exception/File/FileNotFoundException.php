<?php

declare(strict_types=1);

namespace App\Exception\File;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FileNotFoundException extends NotFoundHttpException
{
    public const MESSAGE = 'File not found in server.';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}