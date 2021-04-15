<?php

declare(strict_types=1);

namespace App\Tests\Functional\Group;

use App\Api\Action\Group\AcceptRequest;
use App\Exception\Group\UserAlreadyMemberOfGroupException;
use App\Exception\GroupRequest\GroupRequestNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;

class AcceptRequestTest extends GroupTestBase
{
    private const FIXTURE_TOKEN = '234567';

    public function testAcceptRequest(): void
    {
        $payload = [
            'userId' => $this->getManoloId(),
            'token' => self::FIXTURE_TOKEN
        ];

        self::$manolo->request(
            'PUT',
            sprintf('%s/%s/accept_request', $this->endpoint, $this->getPeterGroupId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$manolo->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(AcceptRequest::MESSAGE, $responseData['message']);
    }

    public function testAcceptAnAcceptedRequest(): void
    {
        $this->testAcceptRequest();

        $payload = [
            'userId' => $this->getManoloId(),
            'token' => self::FIXTURE_TOKEN
        ];

        self::$manolo->request(
            'PUT',
            sprintf('%s/%s/accept_request', $this->endpoint, $this->getPeterGroupId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$manolo->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertEquals(GroupRequestNotFoundException::class, $responseData['class']);
    }
}