<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\User;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;
use App\Service\User\ResetPasswordService;

class ResetPasswordServiceTest extends UserServiceTestBase
{
    private const RESET_PASSWORD_TOKEN = 'abcde';
    private const PASSWORD = 'new-password';

    private ResetPasswordService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new ResetPasswordService(
            $this->userRepository,
            $this->encoderService
        );
    }

    public function testResetPasswordService(): void
    {
        $resetPasswordToken = self::RESET_PASSWORD_TOKEN;
        $password = self::PASSWORD;
        $user = new User('user', 'user@api.com');

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneByIdAndResetPasswordToken')
            ->with($user->getId(), $resetPasswordToken)
            ->willReturn($user);

        $newUser = $this->service->reset($user->getId(), $resetPasswordToken, $password);

        $this->assertInstanceOf(User::class, $user);
        $this->assertNull($newUser->getResetPasswordToken());
    }

    public function testResetPasswordServiceForNonExistingUser(): void
    {
        $resetPasswordToken = self::RESET_PASSWORD_TOKEN;
        $password = self::PASSWORD;
        $user = new User('user', 'user@api.com');

        $this->userRepository
            ->expects($this->exactly(1))
            ->method('findOneByIdAndResetPasswordToken')
            ->with($user->getId(), $resetPasswordToken)
            ->willThrowException(new UserNotFoundException());

        $this->expectException(UserNotFoundException::class);

        $this->service->reset($user->getId(), $resetPasswordToken, $password);
    }
}