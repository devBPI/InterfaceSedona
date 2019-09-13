<?php

namespace App\Repository;

use App\Entity\UserSelectionList;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserSelectionListRepository
 * @package App\Repository
 */
class UserSelectionListRepository extends EntityRepository
{
    /**
     * @param string $uid
     * @return UserSelectionList[]
     */
    public function findAllOrderedByPosition(string $uid): array
    {
        return $this->createQueryBuilder('list')
            ->where('list.user_uid = :user')
            ->orderBy('list.position')
            ->getQuery()
            ->setParameter('user', $uid)
            ->getResult();
    }

    /**
     * @param array $ids
     * @return UserSelectionList[]
     */
    public function findByIds(array $ids): array
    {
        return $this->createQueryBuilder('list')
            ->where('list.ids IN :ids')
            ->orderBy('list.position')
            ->getQuery()
            ->setParameter('ids', $ids)
            ->getResult();
    }
}
