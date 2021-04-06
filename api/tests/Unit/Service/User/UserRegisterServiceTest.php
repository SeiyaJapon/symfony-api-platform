<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\User;

use App\Entity\User;
use App\Exception\Password\PasswordException;
use App\Exception\User\UserAlreadyExistException;
use App\Service\User\UserRegisterService;
use Doctrine\ORM\ORMException;
use Symfony\Component\Messenger\Envelope;

class UserRegisterServiceTest extends UserServiceTestBase
{
    private const NAME = 'new-user';
    private const EMAIL = 'new-user@api.com';
    private const PASSWORD = 'password';

    private UserRegisterService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new UserRegisterService(
            $this->userRepository,
            $this->encoderService,
            $this->messageBus
        );
    }

    public function testUserRegister(): void
    {
        $name = self::NAME;
        $email = self::EMAIL;
        $password = self::PASSWORD;

        $message = $this->getMockBuilder(UserRegisteredMessage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->messageBus
            ->expects($this->exactly(1))
            ->method('dispatch')
            ->with($this->isType('object'), $this->isType('array'))
            ->willReturn(new Envelope($message));

        $user = $this->service->create($name, $email, $password);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($name, $user->getName());
    }

    public function testUserRegisterForInvalidPassword(): void
    {
        $name = self::NAME;
        $email = self::EMAIL;
        $password = self::PASSWORD;

        $this->encoderService
            ->expects($this->exactly(1))
            ->method('generateEncodedPassword')
            ->with($this->isType('object'), $this->isType('string'))
            ->willThrowException(new PasswordException());

        $this->expectException(PasswordException::class);

        $this->service->create($name, $email, $password);
    }

    public function testUserRegisterForAlreadyExistingUser(): void
    {
        $name = self::NAME;
        $email = self::EMAIL;
        $password = self::PASSWORD;

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('save')
            ->with($this->isType('object'))
            ->willThrowException(new ORMException());

        $this->expectException(UserAlreadyExistException::class);

        $this->service->create($name, $email, $password);
    }
}