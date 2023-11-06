<?php

namespace App\Repository;

use App\Entity\Toy;
use App\VO\Protocol\BaseFilters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Toy>
 *
 * @method Toy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Toy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Toy[]    findAll()
 * @method Toy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToyRepository extends ExtendedEntityRepository
{
    /**
     * @param $filters
     * @return QueryBuilder
     */
    protected function applyQueryFilters($filters): QueryBuilder
    {
        $qb = $this->createQueryBuilder('o');
        if (isset($filters->search)) {
            $qb->where('LOWER(o.name) LIKE :name')
                ->setParameter('name', '%' . strtolower($filters->search) . '%');
        }
        return $qb;
    }
}
