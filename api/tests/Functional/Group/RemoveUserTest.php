<?php

declare(strict_types=1);

namespace App\Tests\Functional\Group;

use App\Api\Action\Group\RemoveUser;
use App\Exception\Group\OwnerCannotBeDeletedException;
use App\Exception\Group\UserNotMemberOfGroupException;
use Symfony\Component\HttpFoundation\JsonResponse;

class RemoveUserTest extends GroupTestBase
{
    private const FIXTURE_TOKEN = '234567';

    public function testRemoveUserFromGroup(): void
    {
        $this->addUserToGroup();

        $payload = ['userId' => $this->getManoloId()];

        self::$peter->request(
            'PUT',
            sprintf('%s/%s/remove_user', $this->endpoint, $this->getPeterGroupId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(RemoveUser::MESSAGE, $responseData['message']);
    }

    public function testRemoveTheOwner(): void
    {
        $this->addUserToGroup();

        $payload = ['userId' => $this->getPeterId()];

        self::$peter->request(
            'PUT',
            sprintf('%s/%s/remove_user', $this->endpoint, $this->getPeterGroupId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_CONFLICT, $response->getStatusCode());
        $this->assertEquals(OwnerCannotBeDeletedException::MESSAGE, $responseData['message']);
    }

    public function testRemoveNotAMember(): void
    {
        $payload = ['userId' => $this->getPeterId()];

        self::$manolo->request(
            'PUT',
            sprintf('%s/%s/remove_user', $this->endpoint, $this->getManoloGroupId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$manolo->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
        $this->assertEquals(UserNotMemberOfGroupException::MESSAGE, $responseData['message']);
    }

    private function addUserToGroup(): void
    {
        $payload = [
            'userId' => $this->getManoloId(),
            'token' => self::FIXTURE_TOKEN
        ];

        self::$peter->request(
            'PUT',
            sprintf('%s/%s/accept_request', $this->endpoint, $this->getPeterGroupId()),
            [],
            [],
            [],
            json_encode($payload)
        );
    }
}