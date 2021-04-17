<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TestBase extends WebTestCase
{
    use FixturesTrait;
    use RecreateDatabaseTrait;

    protected static ?KernelBrowser $client = null;
    protected static ?KernelBrowser $peter = null;
    protected static ?KernelBrowser $manolo = null;
    protected static ?KernelBrowser $juan = null;

    protected function setUp()
    {
        if (null === self::$client) {
            self::$client = static::createClient();
            self::$client->setServerParameters(
                [
                    'CONTENT_TYPE' => 'application/json',
                    'HTTP_ACCEPT' => 'application/ld+json'
                ]
            );
        }

        if (null === self::$peter) {
            self::$peter = clone self::$client;
            $this->createAuthenticateUser(self::$peter, 'peter@api.com');
        }

        if (null === self::$manolo) {
            self::$manolo = clone self::$client;
            $this->createAuthenticateUser(self::$manolo, 'manolo@api.com');
        }

        if (null === self::$juan) {
            self::$juan = clone self::$client;
            $this->createAuthenticateUser(self::$juan, 'juan@api.com');
        }
    }

    protected function getResponseData(Response $response): array
    {
        return json_decode($response->getContent(), true);
    }

    protected function initDbConnection(): Connection
    {
        return $this->getContainer()->get('doctrine')->getConnection();
    }

    /**
     * @return false|mixed
     * @throws Exception
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    protected function getPeterId()
    {
        return $this->initDbConnection()->executeQuery('SELECT id FROM user WHERE email = "peter@api.com"')->fetchOne();
        // return $this->initDbConnection()->executeQuery('SELECT id FROM user WHERE email = "peter@api.com"')->fetchOne();
    }

    /**
     * @return false|mixed
     * @throws Exception
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    protected function getPeterGroupId()
    {
        return $this->initDbConnection()->executeQuery('SELECT id FROM user_group WHERE name = "Peter Group"')->fetchOne();
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     */
    protected function getPeterExpenseCategoryId()
    {
        return $this->initDbConnection()->executeQuery('SELECT id FROM category WHERE name = "Peter Expense Category"')->fetchOne();
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     */
    protected function getPeterGroupExpenseCategoryId()
    {
        return $this->initDbConnection()->executeQuery('SELECT id FROM category WHERE name = "Peter Group Expense Category"')->fetchOne();
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     */
    protected function getPeterMovementId()
    {
        return $this->initDbConnection()->executeQuery('SELECT id FROM movement WHERE amount = 100')->fetchOne();
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     */
    protected function getPeterGroupMovementId()
    {
        return $this->initDbConnection()->executeQuery('SELECT id FROM movement WHERE amount = 1000')->fetchOne();
    }

    /**
     * @return false|mixed
     * @throws Exception
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    protected function getManoloId()
    {
        return $this->initDbConnection()->executeQuery('SELECT id FROM user WHERE email = "manolo@api.com"')->fetchOne();
        // return $this->initDbConnection()->executeQuery('SELECT id FROM user WHERE email = "manolo@api.com"')->fetchOne();
    }

    /**
     * @return false|mixed
     * @throws Exception
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    protected function getManoloGroupId()
    {
        return $this->initDbConnection()->executeQuery('SELECT id FROM user_group WHERE name = "Manolo Group"')->fetchOne();
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     */
    protected function getManoloExpenseCategoryId()
    {
        return $this->initDbConnection()->executeQuery('SELECT id FROM category WHERE name = "Manolo Expense Category"')->fetchOne();
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     */
    protected function getManoloGroupExpenseCategoryId()
    {
        return $this->initDbConnection()->executeQuery('SELECT id FROM category WHERE name = "Manolo Group Expense Category"')->fetchOne();
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     */
    protected function getManoloMovementId()
    {
        return $this->initDbConnection()->executeQuery('SELECT id FROM movement WHERE amount = 200')->fetchOne();
    }

    /**
     * @return false|mixed
     *
     * @throws DBALException
     */
    protected function getManoloGroupMovementId()
    {
        return $this->initDbConnection()->executeQuery('SELECT id FROM movement WHERE amount = 2000')->fetchOne();
    }

    private function createAuthenticateUser(KernelBrowser &$client, string $email): void
    {
        $user = $this->getContainer()->get('App\Repository\UserRepository')->findOneByEmailOrFail($email);
        $token = $this
            ->getContainer()
            ->get('Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface')
            ->create($user);

        $client->setServerParameters(
            [
                'HTTP_Authorization' => sprintf('Bearer %s', $token),
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/ld+json'
            ]
        );
    }
}