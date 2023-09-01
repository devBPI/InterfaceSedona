<?php

namespace App\Repository;

use App\Entity\UserSelectionDocument;
use App\Entity\UserSelectionList;
use App\Model\Exception\NoResultException;
use App\Model\LdapUser;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * Class UserSelectionListRepository
 * @package App\Repository
 */
class UserSelectionDocumentRepository extends EntityRepository
{
    /**
     * @param LdapUser $user
     * @param array $permalinks
     * @return array|null
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getByPermalinks(LdapUser $user, array $permalinks=[]): array
    {
            $sql = <<<PGSQL
SELECT
      u0_.permalink     AS permalink,
                max(u0_.title)         AS title,
                max(u0_.url)           AS url,
       max(u0_.author) as author,
       max(u0_.comment) as comment,
       max(u0_.id) as id,
       max(u0_.list_id) as list_id,
       max(u0_.document_type) as document_type,
       max(u0_.creation_date) as creation_date,
       max(u0_.notice_type) as notice_type
FROM user_selection_document u0_
         right JOIN user_selection_list u1_ ON u0_.list_id = u1_.id
WHERE u1_.user_uid = :user
  AND u0_.permalink IN (:permalink)
group by u0_.permalink
PGSQL;
            $rsm = new ResultSetMappingBuilder($this->_em);
            $rsm->addRootEntityFromClassMetadata(UserSelectionDocument::class, 't');
            $query = $this->_em->createNativeQuery($sql, $rsm);
            $query->setParameter('user', $user->getUid())
                ->setParameter('permalink', $permalinks)
            ;
            return $query->getResult();

    }

}
