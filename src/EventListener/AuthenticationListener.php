<?php

namespace App\EventListener;

use App\Service\HistoryService;
use App\Service\SelectionListService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class AuthenticationListener
 * @package App\EventListener
 */
class AuthenticationListener
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var SelectionListService
     */
    private $selectionListService;
    /**
     * @var HistoryService
     */
    private $historyService;

    /**
     * AuthenticationListener constructor.
     * @param EntityManagerInterface $entityManager
     * @param SelectionListService $selectionListService
     * @param HistoryService $historyService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        SelectionListService $selectionListService,
        HistoryService $historyService
    ) {
        $this->entityManager = $entityManager;
        $this->selectionListService = $selectionListService;
        $this->historyService = $historyService;
    }

    /**
     * @param InteractiveLoginEvent $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        if (
            $this->selectionListService->saveDocumentsFromSession() ||
            $this->historyService->saveHistoriesFromSession()
        ) {
            $this->entityManager->flush();
        }
    }
}
