<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\UserHistory;
use App\WordsList;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
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
     * HistoricService constructor.
     * @param EntityManager $entityManager
     * @param TranslatorInterface $translator
     */
    public function __construct(EntityManager $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     * @return string
     * @throws \Doctrine\ORM\ORMException
     */
    public function saveMyHistoric(Request $request)
    {
        $history = new UserHistory($this->setTitleFromRequest($request), $request->request->all());
        $this->entityManager->persist($history);
        $this->entityManager->flush($history);
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

            $mode = $this->translator->trans('page.search.mode.'.($request->get(WordsList::ADVANCED_SEARCH_ACTION) === WordsList::CLICKED ? '1':'0'));

            $this->searchTitle = $this->translator->trans('page.search.title', [
                '%mode%' => $mode,
                '%searchInput%'=> $searchInput,
                '%currentPage%'=> '2',
                '%nbPage%'=> '5'
            ]);
        }

        return $this->searchTitle;
    }

    /**
     * @return array|UserHistory[]
     */
    public function getHistory(): array
    {
        return $this->entityManager->getRepository(UserHistory::class)->findAll();
    }
}
