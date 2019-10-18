<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\SearchHistory;
use App\Entity\UserHistory;
use App\Model\LdapUser;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserHistoryRepository
 * @package App\Repository
 */
class UserHistoryRepository extends EntityRepository
{
    private const DISPLAY_LIMIT = 20;

    /**
     * @param LdapUser $user
     * @return UserHistory[]
     */
    public function getUserHistory(LdapUser $user): array
    {
        return $this->createQueryBuilder('h')
            ->where('h.user_uid = :user')
            ->orderBy('h.last_view_date', Criteria::DESC)
            ->setMaxResults(self::DISPLAY_LIMIT)
            ->getQuery()
            ->setParameter('user', $user->getUid())
            ->getResult()
        ;
    }

    /**
     * @param SearchHistory $searchHistory
     * @param LdapUser $user
     * @return UserHistory|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUserHistoryFromSearchContext(SearchHistory $searchHistory, LdapUser $user): ?UserHistory
    {
        return $this->createQueryBuilder('h')
            ->where('h.user_uid = :user')
            ->andWhere('h.Search = :Search')
            ->getQuery()
            ->setParameter('user', $user->getUid())
            ->setParameter('Search', $searchHistory)
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param \DateTime $date
     * @return mixed
     */
    public function deleteOlderHistories(\DateTime $date)
    {
        return $this->createQueryBuilder('h')
            ->delete()
            ->where('h.last_view_date < :date')
            ->getQuery()
            ->setParameter('date', $date)
            ->getResult();
    }

}
