<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\SearchHistory;
use App\Entity\UserHistory;
use App\Model\Search\ObjSearch;
use App\Repository\UserHistoryRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class HistoryService
 * @package App\Service
 */
final class HistoryService extends AuthenticationService
{
    const SESSION_HISTORY_ID = 'history_session';
    const TITLE_LIMIT = 40;


    /**
     * @var EntityManager
     */
    private $entityManager;
    /***
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * HistoryService constructor.
     *
     * @param EntityManager $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param SessionInterface $session
     * @param TranslatorInterface $translator
     */
    public function __construct(
        EntityManager $entityManager,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session,
        TranslatorInterface $translator
    ) {
        $this->entityManager = $entityManager;
        $this->translator = $translator;

        parent::__construct($tokenStorage, $session);
    }

    /**
     * @return array|UserHistory[]
     */
    public function getHistory(): array
    {
        if ($this->hasConnectedUser()) {
            /** @var UserHistoryRepository $userHistoryRepo */
            $userHistoryRepo = $this->entityManager->getRepository(UserHistory::class);
            return $userHistoryRepo->getUserHistory($this->getUser());
        }

        return $this->getHistoryFromSession();
    }

    /**
     * @return array|SearchHistory[]
     */
    public function getHistoryFromSession(): array
    {
        return array_map(
            function ($document) {
                return unserialize($document);
            },
            $this->getSession(self::SESSION_HISTORY_ID)
        );
    }

    /**
     * @param string $action
     * @param array $listObj
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function applyAction(string $action = null, array $listObj = []): void
    {
        if ($action === 'delete') {
            if ($this->hasConnectedUser()) {
                $this->deleteHistories($listObj);
            } else {
                $this->deleteHistoriesFromSession($listObj);
            }
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
            if ($history->getUserUid() !== $this->getUser()->getUid()) {
                throw new UnauthorizedHttpException(
                    'History '.$history->getId().' don\'t own to '.
                    $this->getUser()->getFirstname().' '.$this->getUser()->getLastname());
            }
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
        /** @var UserHistoryRepository $userHistoryRepo */
        $userHistoryRepo = $this->entityManager->getRepository(UserHistory::class);

        return $userHistoryRepo->deleteOlderHistories($date);
    }

    /**
     * @param ObjSearch $objSearch
     * @param string $serializedData
     * @param bool $savedHistory
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveCurrentSearchInSession(ObjSearch $objSearch, string $serializedData, $savedHistory = false)
    {
        $hash = SearchHistory::getSearchHash($serializedData);

        $this->setSession($hash, $serializedData);

        $objSearch->setContext($hash, $serializedData);

        if ($savedHistory) {
            $this->saveUserHistory($objSearch);
        }
    }

    /**
     * @param ObjSearch $objSearch
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveUserHistory(ObjSearch $objSearch): void
    {
        $historyTitle = $objSearch->getCriteria()->getMyHistoryTitle($this->translator);
        if (strlen($historyTitle) > self::TITLE_LIMIT) {
            $historyTitle = substr($historyTitle,0,self::TITLE_LIMIT);
        }

        $searchHistory = $this->findOrCreateSearchHistory(
            $historyTitle,
            $objSearch);

        $userHistory = $this->findOrCreateUserHistory($searchHistory);

        if (!$this->hasConnectedUser()) {
            $this->appendSession(self::SESSION_HISTORY_ID, [$objSearch->getContextToken() => serialize($userHistory)]);
        } else {
            $this->entityManager->flush();
        }

    }

    /**
     * @param string $title
     * @param ObjSearch $objSearch
     * @return SearchHistory
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function findOrCreateSearchHistory(string $title, ObjSearch $objSearch): SearchHistory
    {

        $data = $objSearch->getContextConfig();
        $parcours = $objSearch->getCriteria()->getParcours();
        $searchHash = SearchHistory::getSearchHash($data);
        $searchHistory = $this->entityManager->getRepository(SearchHistory::class)
            ->find($searchHash);

        if ($searchHistory instanceof SearchHistory) {
            return $searchHistory;
        }

        $searchHistory = new SearchHistory($title, $data, $parcours);

        $this->entityManager->persist($searchHistory);
        $this->entityManager->flush();

        return $searchHistory;
    }

    /**
     * @param SearchHistory $searchHistory
     * @return UserHistory
     * @throws \Doctrine\ORM\ORMException
     */
    private function findOrCreateUserHistory(SearchHistory $searchHistory): UserHistory
    {

        if ($this->hasConnectedUser()) {
            /** @var UserHistoryRepository $userHistoryRepo */
            $userHistoryRepo = $this->entityManager->getRepository(UserHistory::class);
            $userHistory = $userHistoryRepo->getUserHistoryFromSearchContext($searchHistory, $this->getUser());
        } else {
            $userHistory = $this->getUserHistoryFromSession($searchHistory);
        }

        if ($userHistory instanceof UserHistory) {
            $userHistory->incrementCount();

            return $userHistory;
        }

        $userHistory = new UserHistory($searchHistory);
        if ($this->hasConnectedUser()) {
            $userHistory->setUser($this->getUser());
            $this->entityManager->persist($userHistory);
        }

        return $userHistory;
    }

    /**
     * @param SearchHistory $searchHistory
     * @return UserHistory|null
     */
    private function getUserHistoryFromSession(SearchHistory $searchHistory): ?UserHistory
    {
        $histories = $this->getSession(self::SESSION_HISTORY_ID);

        if (array_key_exists($searchHistory->getId(), $histories)) {
            return unserialize($histories[$searchHistory->getId()]);
        }

        return null;
    }

    /**
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function saveHistoriesFromSession(): bool
    {
        if ($this->hasSession(self::SESSION_HISTORY_ID)) {
            foreach ($this->getSession(self::SESSION_HISTORY_ID) as $history) {
                /** @var UserHistory $userHistory */
                $userHistory = unserialize($history);
                $userHistory->setUser($this->getUser());

                $searchHistory = $this->entityManager->getRepository(SearchHistory::class)
                    ->find($userHistory->getUrl());
                if (!$searchHistory instanceof SearchHistory) {
                    $searchHistory = new SearchHistory(
                        $userHistory->getTitle(),
                        $userHistory->getSearch()->getQueryString()
                    );
                    $this->entityManager->persist($searchHistory);
                }
                $userHistory->setSearch($searchHistory);

                $this->entityManager->persist($userHistory);
            }

            return true;
        }

        return false;
    }

    /**
     * @param array $keys
     */
    private function deleteHistoriesFromSession(array $keys = []): void
    {
        $newHists = array_filter(
            $this->session->get(self::SESSION_HISTORY_ID, []),
            function ($key) use ($keys) {
                return !in_array($key, $keys);
            },
            ARRAY_FILTER_USE_KEY);

        $this->setSession(self::SESSION_HISTORY_ID, $newHists);
    }

}
