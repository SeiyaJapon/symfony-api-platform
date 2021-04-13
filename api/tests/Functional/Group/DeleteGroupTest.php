<?php

declare(strict_types=1);

namespace App\Tests\Functional\Group;

use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteGroupTest extends GroupTestBase
{
    public function testDeleteGroup(): void
    {
        self::$peter->request('DELETE', sprintf('%s/%s', $this->endpoint, $this->getPeterGroupId()));

        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, self::$peter->getResponse()->getStatusCode());
    }

    public function testDeleteAnotherGroup(): void
    {
        self::$manolo->request('DELETE', sprintf('%s/%s', $this->endpoint, $this->getPeterGroupId()));

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, self::$manolo->getResponse()->getStatusCode());
    }
}