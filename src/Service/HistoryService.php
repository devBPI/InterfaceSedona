<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\SearchHistory;
use App\Entity\UserHistory;
use App\Model\Exception\SearchHistoryException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class HistoryService
 * @package App\Service
 */
final class HistoryService extends AuthenticationService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * HistoryService constructor.
     *
     * @param EntityManager $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param SessionInterface $session
     */
    public function __construct(
        EntityManager $entityManager,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session
    ) {
        $this->entityManager = $entityManager;

        parent::__construct($tokenStorage, $session);
    }

    /**
     * @param string $title
     * @param string $data
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveUserHistory(string $title, string $data): void
    {
        $searchHash = SearchHistory::getSearchHash($data);
        $searchHistory = $this->entityManager->getRepository(SearchHistory::class)
            ->find($searchHash);
        if (!$searchHistory instanceof SearchHistory) {
            $searchHistory = new SearchHistory(
                $title,
                $data
            );
            $this->entityManager->persist($searchHistory);
        }

        $userHistory = $this->getUserHistoryOfSearchHistory($searchHistory);
        if ($userHistory instanceof UserHistory) {
            $userHistory->incrementCount();
        } else {
            $userHistory = new UserHistory($searchHistory);
            $userHistory->setUserUid($this->getUserIdOrSessionId());
            $this->entityManager->persist($userHistory);
        }

        $this->entityManager->flush();
    }


    /**
     * @param SearchHistory $searchHistory
     * @return UserHistory|null
     */
    private function getUserHistoryOfSearchHistory(SearchHistory $searchHistory): ?UserHistory
    {
        return $this->entityManager->getRepository(UserHistory::class)->findOneBy(
            ['Search' => $searchHistory, 'user_uid' => $this->getUserIdOrSessionId()]
        );
    }

    /**
     * @return string
     */
    private function getUserIdOrSessionId(): string
    {
        if ($this->hasConnectedUser()) {
            return $this->getUser()->getUid();
        }

        return $this->session->getId();
    }

    /**
     * @return array|UserHistory[]
     */
    public function getHistory(): array
    {
        return $this->entityManager->getRepository(UserHistory::class)->findBy(
            ['user_uid' => $this->getUserIdOrSessionId()]
        );
    }

    /**
     * @param string $hash
     * @return SearchHistory
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function getSearchHistoryByHash(string $hash): SearchHistory
    {
        $searchHistory = $this->entityManager->getRepository(SearchHistory::class)->find($hash);
        if (!$searchHistory instanceof SearchHistory) {
            throw new SearchHistoryException('No search for hash '.$hash);
        }

        $this->getUserHistoryOfSearchHistory($searchHistory)->incrementCount();
        $this->entityManager->flush();

        return $searchHistory;
    }

    /**
     * @param $action
     * @param $listObj
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function applyAction($action, $listObj): void
    {
        if ($action === 'delete') {
            $this->deleteHistories($listObj);
        }
    }

    /**
     * @param array $list
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteHistories(array $list): void
    {
        $histories = $this->entityManager->getRepository(UserHistory::class)
            ->findBy(['id' => $list]);
        foreach ($histories as $history) {
            $this->entityManager->remove($history);
        }

        $this->entityManager->flush();
    }

    /**
     * @param \DateTime $date
     * @return \App\Entity\UserSelectionList[]
     */
    public function deleteHistoriesOlderThanDate(\DateTime $date)
    {
        return $this->entityManager->getRepository(UserHistory::class)
            ->deleteOlderHistories($date);
    }

}
