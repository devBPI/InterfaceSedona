<?php
declare(strict_types=1);

namespace App\Service;

use App\Controller\SearchController;
use App\Entity\SearchHistory;
use App\Entity\UserHistory;
use App\Model\Exception\SearchHistoryException;
use App\Model\LdapUser;
use App\WordsList;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class HistoricService
 * @package App\Service
 */
final class HistoricService
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var Translator
     */
    private $translator;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * HistoricService constructor.
     * @param EntityManager $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param TranslatorInterface $translator
     */
    public function __construct(
        EntityManager $entityManager,
        TokenStorageInterface $tokenStorage,
        TranslatorInterface $translator
    ) {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param Request $request
     * @param string $title
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveMyHistoric(Request $request, string $title): void
    {
        $searchHash = SearchHistory::getSearchHash($request->query->all());
        $searchHistory = $this->entityManager->getRepository(SearchHistory::class)->find($searchHash);
        if (!$searchHistory instanceof SearchHistory) {
            $searchHistory = new SearchHistory(
                $title,
                $request->query->all()
            );
            $this->entityManager->persist($searchHistory);
        }

        if (
            $this->tokenStorage->getToken() instanceof TokenInterface &&
            $this->tokenStorage->getToken()->getUser() instanceof LdapUser
        ) {
            $userHistory = $this->getUserHistory($searchHistory);
            if ($userHistory instanceof UserHistory) {
                $userHistory->incrementCount();
            } else {
                $userHistory = new UserHistory($searchHistory);
                $userHistory->setUserUid($this->tokenStorage->getToken()->getUser()->getUid());
                $this->entityManager->persist($userHistory);
            }
        }

        $this->entityManager->flush();
    }


    /**
     * @param SearchHistory $searchHistory
     * @return UserHistory|null
     */
    private function getUserHistory(SearchHistory $searchHistory): ?UserHistory
    {
        return $this->entityManager->getRepository(UserHistory::class)->findOneBy(
            ['Search' => $searchHistory, 'user_uid' => $this->tokenStorage->getToken()->getUser()->getUid()]
        );
    }

    /**
     * @return array|UserHistory[]
     */
    public function getHistory(): array
    {
        if (
            $this->tokenStorage->getToken() instanceof TokenInterface &&
            $this->tokenStorage->getToken()->getUser() instanceof LdapUser
        ) {
            return $this->entityManager->getRepository(UserHistory::class)->findBy(
                ['user_uid' => $this->tokenStorage->getToken()->getUser()->getUid()]
            );
        }

        return $this->entityManager->getRepository(UserHistory::class)->findAll();
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

        if (
            $this->tokenStorage->getToken() instanceof TokenInterface &&
            $this->tokenStorage->getToken()->getUser() instanceof LdapUser
        ) {
            $this->getUserHistory($searchHistory)->incrementCount();
            $this->entityManager->flush();
        }

        return $searchHistory;
    }

    /**
     * @param array $list
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteHistories(array $list): void
    {
        foreach ($this->entityManager->getRepository(UserHistory::class)->findBy(['id' => $list]) as $history) {
            $this->entityManager->remove($history);
        }

        $this->entityManager->flush();
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
}
