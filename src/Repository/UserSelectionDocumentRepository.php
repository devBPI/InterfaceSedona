<?php

namespace App\Repository;

use App\Entity\UserSelectionDocument;
use App\Entity\UserSelectionList;
use App\Model\LdapUser;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserSelectionListRepository
 * @package App\Repository
 */
class UserSelectionDocumentRepository extends EntityRepository
{
    /**
     * @param LdapUser $user
     * @param array $permalinks
     * @return array
     */
    public function findAllOrderedByPermalinks(LdapUser $user, array $permalinks=[], array $listId=[]): array
    {
        if($permalinks===[]){
            return [];
        }

        return
            $this->createQueryBuilder('doc')
            ->join('doc.List', 'list')
            ->where('list.user_uid = :user')
            ->andWhere(sprintf("doc.permalink in ('%s')",  implode("','", $permalinks)))
            ->orderBy('list.position')
            ->getQuery()
            ->setParameter('user', $user->getUid())
            ->getResult();
    }
}
