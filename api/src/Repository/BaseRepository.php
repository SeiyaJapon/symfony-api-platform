<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\Mapping\MappingException;
use Doctrine\Persistence\ObjectManager;
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
     * @return ObjectManager|EntityManager
     */
    public function getEntityManager()
    {
        $entityManager = $this->managerRegistry->getManager();

        return ($entityManager->isOpen()) ? $entityManager : $this->managerRegistry;
    }

    /**
     * @param object $entity
     * @throws ORMException
     */
    protected  function persistEntity(object $entity) : void
    {
        $this->getEntityManager()->persist($entity);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws MappingException
     */
    protected  function flushData() : void
    {
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
    }

    /**
     * @param object $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected  function saveEntity(object $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @param object $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected  function removeEntity(object $entity)
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @param string $query
     * @param array  $params
     * @return array
     * @throws Exception
     */
    protected  function executeFetchQuery(string $query, array $params) : array
    {
        return $this->connection->executeQuery($query, $params)->fetchAll();
    }

    /**
     * @param string $query
     * @param array  $params
     * @throws Exception
     */
    protected  function executeQuery(string $query, array $params) : void
    {
        $this->connection->executeQuery($query, $params);
    }

    abstract protected static function entityClass() : string;
}