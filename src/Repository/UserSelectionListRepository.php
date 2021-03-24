<?php

namespace App\Repository;

use App\Entity\UserSelectionList;
use App\Model\LdapUser;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserSelectionListRepository
 * @package App\Repository
 */
class UserSelectionListRepository extends EntityRepository
{
    /**
     * @param LdapUser $user
     * @return UserSelectionList[]
     */
    public function findAllOrderedByPosition(LdapUser $user): array
    {
        return $this->createQueryBuilder('list')
            ->where('list.user_uid = :user')
            ->orderBy('list.position')
            ->getQuery()
            ->setParameter('user', $user->getUid())
            ->getResult();
    }

    /**
     * @param LdapUser $user
     * @param array $ids
     * @return UserSelectionList[]
     */
    public function findByIds(LdapUser $user, array $ids): array
    {
        return $this->createQueryBuilder('list')
            ->where('list.id IN (:ids)')
            ->andWhere('list.user_uid = :user')
            ->orderBy('list.position')
            ->getQuery()
            ->setParameter('ids', $ids, Connection::PARAM_INT_ARRAY)
            ->setParameter('user', $user->getUid())
            ->getResult();
    }

    /**
     * @param LdapUser $user
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getMaxPositionOfUserList(LdapUser $user): int
    {
        return $this->createQueryBuilder('list')
            ->where('list.user_uid = :user')
            ->select('max(list.position)')
            ->getQuery()
            ->setParameter('user', $user->getUid())
            ->getSingleScalarResult() ?? 0;
    }

    /**
     * @param LdapUser $user
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCountDocuments(LdapUser $user): int
    {
        return $this->createQueryBuilder('list')
            ->join('list.documents', 'doc')
            ->where('list.user_uid = :user')
            ->select('count(doc.id)')
            ->getQuery()
            ->setParameter('user', $user->getUid())
            ->getSingleScalarResult();
    }

    /**
     * @param LdapUser $user
     * @param $permalink
     * @param UserSelectionList|null $list
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getList(LdapUser $user, $permalink, UserSelectionList $list =null):int
    {
        $d =  $this->createQueryBuilder('list')
            ->join('list.documents', 'doc')
            ->where('list.user_uid = :user')
            ->andWhere('doc.permalink = :permalink');
        if($list instanceof UserSelectionList){
            $d->andWhere('list.id = :id');
        }
  $d          ->select('count(doc.id)')
            ->setParameter('user', $user->getUid())
            ->setParameter('permalink', $permalink);
        if($list instanceof UserSelectionList){
            $d->setParameter('id', $list->getId());
        }
        return $d->getQuery()->getSingleScalarResult();

    }

}
