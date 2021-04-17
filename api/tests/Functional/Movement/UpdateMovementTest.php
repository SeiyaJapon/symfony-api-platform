<?php

declare(strict_types=1);

namespace App\Tests\Functional\Movement;

use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateMovementTest extends MovementTestBase
{
    public function testUpdateMovement(): void
    {
        $payload = [
            'category' => sprintf('/api/v1/categories/%s', $this->getPeterExpenseCategoryId()),
            'amount' => 500.05,
        ];

        self::$peter->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $this->getPeterMovementId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($payload['category'], $responseData['category']);
        $this->assertEquals($payload['amount'], $responseData['amount']);
    }

    public function testUpdateAnotherUserMovement(): void
    {
        $payload = [
            'category' => sprintf('/api/v1/categories/%s', $this->getPeterExpenseCategoryId()),
            'amount' => 500.05,
        ];

        self::$manolo->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $this->getPeterMovementId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$manolo->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testUpdateAnotherGroupMovement(): void
    {
        $payload = [
            'category' => sprintf('/api/v1/categories/%s', $this->getPeterExpenseCategoryId()),
            'amount' => 500.05,
        ];

        self::$peter->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $this->getManoloGroupMovementId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$peter->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testUpdateMovementWithAnotherUserCategory(): void
    {
        $payload = [
            'category' => sprintf('/api/v1/categories/%s', $this->getManoloExpenseCategoryId()),
            'amount' => 500.05,
        ];

        self::$peter->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $this->getPeterMovementId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$peter->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testUpdateMovementWithAnotherGroupCategory(): void
    {
        $payload = [
            'category' => sprintf('/api/v1/categories/%s', $this->getManoloGroupExpenseCategoryId()),
            'amount' => 500.05,
        ];

        self::$peter->request(
            'PUT',
            sprintf('%s/%s', $this->endpoint, $this->getPeterMovementId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$peter->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
