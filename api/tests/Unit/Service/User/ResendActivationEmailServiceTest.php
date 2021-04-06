<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\User;

use App\Entity\User;
use App\Exception\User\UserIsActiveException;
use App\Messenger\Message\UserRegisteredMessage;
use App\Service\User\ResendActivationEmailService;
use Symfony\Component\Messenger\Envelope;

class ResendActivationEmailServiceTest extends UserServiceTestBase
{
    private const EMAIL = 'email@api.com';

    private ResendActivationEmailService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new ResendActivationEmailService(
            $this->userRepository,
            $this->messageBus
        );
    }

    public function testResendActivationEmailService(): void
    {
        $email = self::EMAIL;
        $user = new User('name', $email);

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneByEmailOrFail')
            ->with($email)
            ->willReturn($user);

        $message = $this->getMockBuilder(UserRegisteredMessage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->messageBus
            ->expects($this->exactly(1))
            ->method('dispatch')
            ->with($this->isType('object'), $this->isType('array'))
            ->willReturn(new Envelope($message));

        $this->service->resend($email);
    }

    public function testResendActivationEmailServiceForActiveUser(): void
    {
        $email = self::EMAIL;
        $user = new User('name', $email);
        $user->setActive(true);
        $user->setToken(null);

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneByEmailOrFail')
            ->with($email)
            ->willReturn($user);

        $this->expectException(UserIsActiveException::class);

        $this->service->resend($email);
    }
}
