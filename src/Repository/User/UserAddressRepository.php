<?php

namespace App\Repository\User;

use App\Entity\User\UserAddress;
use App\Repository\ExtendedEntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * @method UserAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAddress[]    findAll()
 * @method UserAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAddressRepository extends ExtendedEntityRepository
{

    /**
     * @param $filters
     * @return QueryBuilder
     */
    protected function applyQueryFilters($filters): QueryBuilder
    {
        return $this->createQueryBuilder('o');
    }
}
