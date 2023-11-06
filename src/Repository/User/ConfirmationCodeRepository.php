<?php

namespace App\Repository\User;

use App\Entity\User\ConfirmationCode;
use App\Entity\User\User;
use App\Repository\ExtendedEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

/**
 * @method ConfirmationCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfirmationCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfirmationCode[]    findAll()
 * @method ConfirmationCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfirmationCodeRepository extends ExtendedEntityRepository
{
    /**
     * @param $filters
     * @return QueryBuilder
     */
    protected function applyQueryFilters($filters): QueryBuilder
    {
        return $this->createQueryBuilder('o');
    }

    public function findOneByCode(string $code): ?ConfirmationCode
    {
        return $this->findOneBy([
            'code' => $code
        ]);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findActivePasswordResetCode(User $user)
    {
        $expDate = $this->createQueryBuilder('e')
            ->select('max(e.expireAt)')
            ->where('e.user=:user')
            ->andWhere('e.action=:action')
            ->setParameters(['user' => $user->getId(),
                'action'=> ConfirmationCode::ACTION_PASSWORD_RESET])
            ->getQuery()
            ->getSingleScalarResult()
        ;
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.user=:user',
                'c.action=:action')
            ->andWhere('c.expireAt=:expire')
            ->setParameters(['user' => $user->getId(),
                'action'=> ConfirmationCode::ACTION_PASSWORD_RESET,
                'expire'=> $expDate]);

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findActiveEmailConfirmationCode(User $user)
    {
        $expDate = $this->createQueryBuilder('e')
            ->select('max(e.expireAt)')
            ->where('e.user=:user')
            ->andWhere('e.action=:action')
            ->setParameters(['user' => $user->getId(),
                'action'=> ConfirmationCode::ACTION_EMAIL_CONFIRMATION])
            ->getQuery()
            ->getSingleScalarResult()
        ;
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.user=:user',
                'c.action=:action')
            ->andWhere('c.expireAt=:expire')
            ->setParameters(['user' => $user->getId(),
                'action'=> ConfirmationCode::ACTION_EMAIL_CONFIRMATION,
                'expire'=> $expDate]);

        return $qb->getQuery()->getSingleResult();
    }

}
