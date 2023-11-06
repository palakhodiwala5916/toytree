<?php

namespace App\Repository\Category;

use App\Entity\Category\Category;
use App\Repository\ExtendedEntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ExtendedEntityRepository
{

    protected function applyQueryFilters($filters): QueryBuilder
    {
        $qb = $this->createQueryBuilder('o');
        return $qb;
    }
}
