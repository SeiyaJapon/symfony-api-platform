<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Exception\User\UserNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class UserRepository extends BaseRepository
{
    public function findOneByEmailOrFail(string $email) : User
    {
        if (null === $user = $this->objectRepository->findOneBy(['email' => $email]))
        {
            UserNotFoundException::fromEmail($email);
        }

        return $user;
    }

    public function findOneInactiveByIdAndTokenOrFail(string $id, string $token) : User
    {
        if (null === $user = $this->objectRepository->findOneBy(['id' => $id, 'token' => $token, 'active' => false]))
        {
            UserNotFoundException::fromUserIdAndToken($id, $token);
        }

        return $user;
    }

    /**
     * @param User $user
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(User $user) : void
    {
        $this->saveEntity($user);
    }

    /**
     * @param User $user
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(User $user) : void
    {
        $this->removeEntity($user);
    }

    protected static function entityClass(): string
    {
        return User::class;
    }
}