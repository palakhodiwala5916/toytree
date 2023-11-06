<?php

namespace App\Repository\User;

use App\Entity\User\User;
use App\Repository\ExtendedEntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ExtendedEntityRepository
{
    /**
     * @param $filters
     * @return QueryBuilder
     */
    protected function applyQueryFilters($filters): QueryBuilder
    {
        return $this->createQueryBuilder('o');
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findOneByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * @param string $id
     * @return User|null
     */
    public function findOneByAppleId(string $id): ?User
    {
        return $this->findOneBy(['appleId' => $id]);
    }

    /**
     * @param string $id
     * @return User|null
     */
    public function findOneByGoogleId(string $id): ?User
    {
        return $this->findOneBy(['googleId' => $id]);
    }

    /**
     * @param string $id
     * @return User|null
     */
    public function findOneByFacebookId(string $id): ?User
    {
        return $this->findOneBy(['facebookId' => $id]);
    }
}
