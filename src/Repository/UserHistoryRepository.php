<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class UserHistoryRepository
 * @package App\Repository
 */
class UserHistoryRepository extends EntityRepository
{
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
