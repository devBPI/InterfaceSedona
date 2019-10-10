<?php

namespace App\Repository;

use App\Entity\UserSelectionDocument;
use App\Entity\UserSelectionList;
use Doctrine\DBAL\Connection;
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
     * @param string $uid
     * @return UserSelectionList[]
     */
    public function findByIds(array $ids, string $uid): array
    {
        return $this->createQueryBuilder('list')
            ->where('list.id IN (:ids)')
            ->andWhere('list.user_uid = :user')
            ->orderBy('list.position')
            ->getQuery()
            ->setParameter('ids', $ids, Connection::PARAM_INT_ARRAY)
            ->setParameter('user', $uid)
            ->getResult();
    }

    /**
     * @param string $uid
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCountDocuments(string $uid): int
    {
        return $this->createQueryBuilder('list')
            ->join(UserSelectionDocument::class, 'doc')
            ->where('list.user_uid = :user')
            ->select('count(doc.id)')
            ->getQuery()
            ->setParameter('user', $uid)
            ->getSingleScalarResult();
    }
}
