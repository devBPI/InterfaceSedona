<?php

namespace App\EventListener;

use App\Model\LdapUser;
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
     * AuthenticationListener constructor.
     * @param EntityManagerInterface $entityManager
     * @param SelectionListService $selectionListService
     */
    public function __construct(EntityManagerInterface $entityManager, SelectionListService $selectionListService)
    {
        $this->entityManager = $entityManager;
        $this->selectionListService = $selectionListService;
    }

    /**
     * @param InteractiveLoginEvent $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        /** @var LdapUser $user */
        $user = $event->getAuthenticationToken()->getUser();

        if ($this->selectionListService->saveDocumentsInSession()) {
            $this->entityManager->flush();
        }
    }
}
