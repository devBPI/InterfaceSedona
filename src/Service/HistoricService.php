<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\SearchHistory;
use App\Entity\User;
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
     * @var string
     */
    private $searchTitle = null;
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
     * @return string
     * @throws \Doctrine\ORM\ORMException
     */
    public function saveMyHistoric(Request $request)
    {
        $searchHash = SearchHistory::getSearchHash($request->request->all());
        $searchHistory = $this->entityManager->getRepository(SearchHistory::class)->find($searchHash);
        if (!$searchHistory instanceof SearchHistory) {
            $searchHistory = new SearchHistory(
                $this->setTitleFromRequest($request),
                $request->request->all()
            );
            $this->entityManager->persist($searchHistory);
        }

        if (
            $this->tokenStorage->getToken() instanceof TokenInterface &&
            $this->tokenStorage->getToken()->getUser() instanceof LdapUser
        ) {
            $userHistory = new UserHistory($searchHistory);
            $userHistory->setUserUid($this->tokenStorage->getToken()->getUser()->getUid());
            $this->entityManager->persist($userHistory);
        }

        $this->entityManager->flush();
    }

    /**
     * @param Request $request
     * @return string
     */
    public function setTitleFromRequest(Request $request): string
    {
        if ($this->searchTitle === null) {
            $searchInput = '';
            if ($request->get(WordsList::ADVANCED_SEARCH_ACTION) === WordsList::CLICKED) {
                foreach ($request->get(WordsList::ADVANCED_SEARCH_LABEL) as $queries) {
                    $searchInput .= $queries[WordsList::TEXT_LABEL];
                }
            } elseif (
                $request->get(WordsList::SIMPLE_SEARCH_LABEL, null) !== null &&
                isset($request->get(WordsList::SIMPLE_SEARCH_LABEL, null)[WordsList::TEXT_LABEL])
            ) {
                $searchInput = $request->get(WordsList::SIMPLE_SEARCH_LABEL, null)[WordsList::TEXT_LABEL];
            }

            $mode = $this->translator->trans(
                'page.search.mode.'.($request->get(
                    WordsList::ADVANCED_SEARCH_ACTION
                ) === WordsList::CLICKED ? '1' : '0')
            );

            $this->searchTitle = $this->translator->trans(
                'page.search.title',
                [
                    '%mode%' => $mode,
                    '%searchInput%' => $searchInput,
                    '%currentPage%' => '2',
                    '%nbPage%' => '5',
                ]
            );
        }

        return $this->searchTitle;
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
     * @throws SearchHistoryException
     */
    public function getSearchHistoryByHash(string $hash): SearchHistory
    {
        $searchHistory = $this->entityManager->getRepository(SearchHistory::class)->find($hash);
        if (!$searchHistory instanceof SearchHistory) {
            throw new SearchHistoryException('No search for hash '.$hash);
        }

        return $searchHistory;
    }
}
