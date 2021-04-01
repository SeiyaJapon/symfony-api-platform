<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;

use App\Tests\Functional\TestBase;

class UserTestBase extends TestBase
{
    protected string $endpoint;

    protected function setUp()
    {
        parent::setUp();

        $this->endpoint = '/api/v1/users';
    }
}