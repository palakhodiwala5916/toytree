<?php

namespace App\Repository\Log;

use App\DependencyInjection\Service\SecurityServiceDI;
use App\Entity\Log\RequestLog;
use App\Repository\ExtendedEntityRepository;
use App\VO\Protocol\BaseFilters;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\Uuid;

/**
 * @method RequestLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestLog[]|array findAll()
 * @method RequestLog[]|array findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestLogRepository extends ExtendedEntityRepository
{
    use SecurityServiceDI;

    /**
     * @param $filters
     * @return QueryBuilder
     */
    protected function applyQueryFilters($filters): QueryBuilder
    {
        return $this->createQueryBuilder('o');
    }

}
