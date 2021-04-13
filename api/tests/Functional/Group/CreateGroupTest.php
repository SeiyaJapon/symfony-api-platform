<?php

declare(strict_types=1);

namespace App\Tests\Functional\Group;

use App\Exception\Group\CannotCreateGroupForAnotherUserException;
use Symfony\Component\HttpFoundation\JsonResponse;

class CreateGroupTest extends GroupTestBase
{
    private const FORBIDDEN_MESSAGE = CannotCreateGroupForAnotherUserException::MESSAGE;

    public function testCreateGroup(): void
    {
        $payload = [
            'name' => 'My new Group',
            'owner' => sprintf('/api/v1/users/%s', $this->getPeterId())
        ];

        self::$peter->request('POST', $this->endpoint, [], [], [], json_encode($payload));

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($payload['name'], $responseData['name']);
    }

    public function testCreateGroupForAnotherUser(): void
    {
        $payload = [
            'name' => 'My new Group',
            'owner' => sprintf('/api/v1/users/%s', $this->getPeterId())
        ];

        self::$manolo->request( 'POST', $this->endpoint, [], [], [], json_encode($payload));

        $response = self::$manolo->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
        $this->assertEquals(self::FORBIDDEN_MESSAGE, $responseData['message']);
    }
}