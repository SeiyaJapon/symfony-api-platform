<?php

declare(strict_types=1);

namespace App\Service\User;


use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Password\EncoderService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class ResetPasswordService
{
    /** @var UserRepository */
    private UserRepository $userRepository;
    /** @var EncoderService */
    private EncoderService $encoderService;

    public function __construct(UserRepository $userRepository, EncoderService $encoderService)
    {
        $this->userRepository = $userRepository;
        $this->encoderService = $encoderService;
    }

    /**
     * @param string $userId
     * @param string $resetPasswordToken
     * @param string $password
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function reset(string $userId, string $resetPasswordToken, string $password) : User
    {
        $user = $this->userRepository->findOneByIdAndResetPasswordToken($userId, $resetPasswordToken);

        $user->setPassword($this->encoderService->generateEncodedPassword($user, $password));
        $user->setResetPasswordToken(null);

        $this->userRepository->save($user);

        return $user;
    }
}