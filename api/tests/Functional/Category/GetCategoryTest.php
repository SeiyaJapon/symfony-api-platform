<?php

declare(strict_types=1);

namespace App\Tests\Functional\Category;

use Symfony\Component\HttpFoundation\JsonResponse;

class GetCategoryTest extends CategoryTestBase
{
    public function testGetCategory(): void
    {
        $peterExpenseCategoryId = $this->getPeterExpenseCategoryId();

        self::$peter->request('GET', sprintf('%s/%s', $this->endpoint, $peterExpenseCategoryId));

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($peterExpenseCategoryId, $responseData['id']);
    }

    public function testGetGroupCategory(): void
    {
        $peterGroupExpenseCategoryId = $this->getPeterGroupExpenseCategoryId();

        self::$peter->request('GET', sprintf('%s/%s', $this->endpoint, $peterGroupExpenseCategoryId));

        $response = self::$peter->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($peterGroupExpenseCategoryId, $responseData['id']);
    }

    public function testGetAnotherUserCategory(): void
    {
        $peterExpenseCategoryId = $this->getPeterExpenseCategoryId();

        self::$manolo->request('GET', sprintf('%s/%s', $this->endpoint, $peterExpenseCategoryId));

        $response = self::$manolo->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    public function testGetAnotherGroupCategory(): void
    {
        $peterGroupExpenseCategoryId = $this->getPeterGroupExpenseCategoryId();

        self::$manolo->request('GET', sprintf('%s/%s', $this->endpoint, $peterGroupExpenseCategoryId));

        $response = self::$manolo->getResponse();

        $this->assertEquals(JsonResponse::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}