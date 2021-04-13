<?php

declare(strict_types=1);

namespace App\Doctrine\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Group;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CurrentUserExtension implements QueryCollectionExtensionInterface
{
    public const MESSAGE = 'You can\'t retrieve another user groups';

    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function applyToCollection(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $operationName = null
    ) {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    private function addWhere(QueryBuilder $qb, string $resourceClass): void
    {
        /** @var User|null $user */
        $user = $this->tokenStorage->getToken()
            ? $this->tokenStorage->getToken()->getUser()
            : null;

        if (Group::class === $resourceClass) {
            if ($qb->getParameters()->first()->getValue() !== $user->getId()) {
                throw new AccessDeniedHttpException(self::MESSAGE);
            }
        }

        if (User::class === $resourceClass) {
            foreach ($user->getGroups() as $group) {
                if ($qb->getParameters()->first()->getValue() !== $group->getId()) {
                    throw new AccessDeniedHttpException(self::MESSAGE);
                }
            }
        }
    }
}