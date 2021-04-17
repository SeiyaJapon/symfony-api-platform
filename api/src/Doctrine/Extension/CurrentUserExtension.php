<?php

declare(strict_types=1);

namespace App\Doctrine\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Category;
use App\Entity\Group;
use App\Entity\Movement;
use App\Entity\User;
use App\Exception\Group\GroupNotFoundException;
use App\Repository\GroupRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CurrentUserExtension implements QueryCollectionExtensionInterface
{
    public const MESSAGE = 'You can\'t retrieve another user groups';

    private TokenStorageInterface $tokenStorage;
    private GroupRepository $groupRepository;

    public function __construct(TokenStorageInterface $tokenStorage, GroupRepository $groupRepository)
    {
        $this->tokenStorage = $tokenStorage;
        $this->groupRepository = $groupRepository;
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

        $rootAlias = $qb->getRootAliases()[0];

        $paremeterValue = $qb->getParameters()->first()->getValue();

        if (Group::class === $resourceClass) {
            if ($paremeterValue !== $user->getId()) {
                throw new AccessDeniedHttpException(self::MESSAGE);
            }
        }

        if (User::class === $resourceClass) {
            foreach ($user->getGroups() as $group) {
                if ($paremeterValue !== $group->getId()) {
                    throw new AccessDeniedHttpException(self::MESSAGE);
                }
            }
        }

        if (in_array($resourceClass, [Category::class, Movement::class])) {
            $parameterId = $paremeterValue;

            if ($this->isGroupAndIsUserMember($parameterId, $user)) {
                $qb->andWhere(sprintf('%s.group = :parmeterId', $rootAlias));
                $qb->setParameter('parameterId', $parameterId);
            } else {
                $qb->andWhere(sprintf('%s.%s = :user', $rootAlias, $this->getResources()[$resourceClass]));
                $qb->andWhere(sprintf('%s.group IS NULL', $rootAlias));
                $qb->setParameter('user', $user);
            }
        }
    }

    private function isGroupAndIsUserMember(string $parameterId, User $user): bool
    {
        try {
            return $user->isMemberOfGroup($this->groupRepository->findOneByIdOrFail($parameterId));
        } catch (GroupNotFoundException $exception) {
            return false;
        }
    }

    private function getResources(): array
    {
        return [
            Category::class => 'owner',
            Movement::class => 'owner'
        ];
    }
}