<?php

declare(strict_types=1);

namespace App\Tests\Functional\Group;

use App\Api\Action\Group\SendRequestToUser;
use App\Exception\Group\NotOwnerOfGroupException;
use App\Exception\Group\UserAlreadyMemberOfGroupException;
use Symfony\Component\HttpFoundation\JsonResponse;

class SendRequestToUserTest extends GroupTestBase
{
    public function testSendRequestToUser(): void
    {
        $payload = ['email' => 'manolo@api.com'];

        self::$peter->request(
            'PUT',
            sprintf('%s/%s/send_request', $this->endpoint, $this->getPeterGroupId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(SendRequestToUser::MESSAGE, $responseData['message']);
    }

    public function testSendAnotherRequestToUser(): void
    {
        $payload = ['email' => 'manolo@api.com'];

        self::$juan->request(
            'PUT',
            sprintf('%s/%s/send_request', $this->endpoint, $this->getPeterGroupId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$juan->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
        $this->assertEquals(NotOwnerOfGroupException::MESSAGE, $responseData['message']);
    }

    public function testSendRequestToUserAlreadyMember(): void
    {
        $payload = ['email' => 'peter@api.com'];

        self::$peter->request(
            'PUT',
            sprintf('%s/%s/send_request', $this->endpoint, $this->getPeterGroupId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_CONFLICT, $response->getStatusCode());
        $this->assertEquals(UserAlreadyMemberOfGroupException::MESSAGE, $responseData['message']);
    }
}