<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;

abstract class BaseRepository
{
    /** @var ManagerRegistry */
    private ManagerRegistry $managerRegistry;

    /** @var Connection */
    protected Connection $connection;

    /** @var ObjectRepository */
    protected ObjectRepository $objectRepository;

    public function __construct(ManagerRegistry $managerRegistry, Connection $connection)
    {
        $this->managerRegistry = $managerRegistry;
        $this->connection = $connection;
        $this->objectRepository = $this->getEntityManager()->getRepository($this->entityClass());
    }

    /**
     * @param object $entity
     * @throws \Doctrine\ORM\ORMException
     */
    public function persistEntity(object $entity) : void
    {
        $this->getEntityManager()->persist($entity);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\Persistence\Mapping\MappingException
     */
    public function flushData() : void
    {
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
    }

    abstract protected static function entityClass: string;

    /**
     * @return ObjectRepository|EntityManager
     */
    private function getEntityManager()
    {
        $entityManager = $this->managerRegistry->getManager();

        return ($entityManager->isOpen()) ? $entityManager : $this->managerRegistry;
    }
}