<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Exception\ApiException;
use App\VO\Protocol\BaseFilters;
use App\VO\Protocol\SearchFilters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use function get_class;
use function str_replace;

abstract class ExtendedEntityRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->getEntityNameFromRepositoryName());
    }

    public function find($id, $lockMode = null, $lockVersion = null): ?object
    {
        if ($id === null) {
            return null;
        }

        return parent::find($id, $lockMode, $lockVersion);
    }

    public function getEntityNameFromRepositoryName()
    {
        $class = get_class($this);
        return str_replace(['App\\Repository\\', 'Repository'], ['App\\Entity\\', ''], $class);
    }

    /**
     * @param array|string[] $ids
     * @return AbstractEntity[]|ArrayCollection<int, AbstractEntity>
     * @noinspection PhpDocSignatureInspection
     */
    public function findByIds(array $ids): ArrayCollection
    {
        $qb = $this->createQueryBuilder('o')
            ->where('o.id IN (:ids)')
            ->setParameter('ids', $ids);

        $results = $qb->getQuery()->getResult();

        return new ArrayCollection($results);
    }

    /**
     * @param BaseFilters $filters
     * @return AbstractEntity[]|array
     */
    public function filterBy($filters)
    {
        $qb = $this->applyQueryFilters($filters);

        if ($filters->limit > 0) {
            $qb->setMaxResults($filters->limit);
        }
        if ($filters->offset >= 0) {
            $qb->setFirstResult($filters->offset);
        }

        foreach ($filters->sortBy as $sortBy) {
            $qb->addOrderBy(sprintf('%s.%s', $qb->getRootAliases()[0], $sortBy->key), $sortBy->value);
        }

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @param SearchFilters $filters
     * @return AbstractEntity[]|array
     * @throws ApiException
     */
    public function searchBy(SearchFilters $filters)
    {
        $rc = new \ReflectionClass($this->getEntityNameFromRepositoryName());

        if (!$rc->getProperty($filters->targetProperty)) {
            throw new ApiException('repository.search_filter.error');
        }

        $qb = $this->createQueryBuilder('g')
            ->where('g.' . $filters->targetProperty . ' LIKE :textValue')
            ->setParameter('textValue', '%' . $filters->textValue . '%')
            ->setMaxResults(10);

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @param BaseFilters $filters
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countBy($filters): int
    {
        $queryBuilder = $this->applyQueryFilters($filters);
        $alias = $queryBuilder->getRootAliases()[0];

        return (int) $queryBuilder
            ->select("count($alias.id)")
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param string $sql
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function fetchOne(string $sql, array $params = []): mixed
    {
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);

        return $stmt->executeQuery($params)->fetchAssociative();
    }

    /**
     * @param string $sql
     * @param array $params
     * @return false|mixed
     * @throws Exception
     */
    public function fetchOneAsValue(string $sql, array $params = []): mixed
    {
        $result = $this->fetchOne($sql, $params);
        if (!empty($result) && is_array($result)) {
            return reset($result);
        }

        return $result;
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);

        return $stmt->executeQuery($params)->fetchAllAssociative();
    }

    /**
     * @param string $sql
     * @param array $params
     * @return int
     * @throws Exception
     */
    public function executeSql(string $sql, array $params = []): int
    {
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);

        return $stmt->executeQuery($params)->rowCount();
    }

    /**
     * @param BaseFilters $filters
     */
    protected abstract function applyQueryFilters($filters): QueryBuilder;

    protected function getQuerySQLBuilder(): \Doctrine\DBAL\Query\QueryBuilder
    {
        return $this->getEntityManager()->getConnection()->createQueryBuilder();
    }

}
