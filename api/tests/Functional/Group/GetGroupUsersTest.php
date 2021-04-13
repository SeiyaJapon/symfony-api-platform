<?php

declare(strict_types=1);

namespace App\Tests\Functional\Group;

use App\Doctrine\Extension\CurrentUserExtension;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetGroupUsersTest extends GroupTestBase
{
    private const PETER_QTY_GROUPS = 1;
    private const FORBIDDEN_MESSAGE = CurrentUserExtension::MESSAGE;

    public function testGetGroupUsers(): void
    {
        self::$peter->request('GET', sprintf('%s/%s/users', $this->endpoint, $this->getPeterGroupId()));

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount(self::PETER_QTY_GROUPS, $responseData['hydra:member']);
    }

    public function testGetAnotherGroupUsers(): void
    {
        self::$manolo->request('GET', sprintf('%s/%s/users', $this->endpoint, $this->getPeterId()));

        $response = self::$manolo->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
        $this->assertEquals(self::FORBIDDEN_MESSAGE, $responseData['message']);
    }
}