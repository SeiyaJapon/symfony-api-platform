<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\User;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;
use App\Service\User\ActivateAccountService;
use Symfony\Component\Uid\Uuid;

class ActivateAccountServiceTest extends UserServiceTestBase
{
    private ActivateAccountService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new ActivateAccountService($this->userRepository);
    }

    public function testActivateAccount(): void
    {
        $user = new User('user','user@api.com');
        $id = Uuid::v4()->toRfc4122();
        $token = sha1(uniqid());

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneInactiveByIdAndTokenOrFail')
            ->with($id, $token)
            ->willReturn($user);

        $result = $this->service->activate($id, $token);

        $this->assertInstanceOf(User::class, $result);
        $this->assertNull($result->getToken());
        $this->assertTrue($result->isActive());
    }

    public function testForNonExistingUser(): void
    {
        $id = Uuid::v4()->toRfc4122();
        $token = sha1(uniqid());
        $message = sprintf(UserNotFoundException::MESSAGE_ID_TOKEN, $id, $token);

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneInactiveByIdAndTokenOrFail')
            ->with($id, $token)
            ->willThrowException(new UserNotFoundException($message));

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage($message);

        $this->service->activate($id, $token);
    }
}