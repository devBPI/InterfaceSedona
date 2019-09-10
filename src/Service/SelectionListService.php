<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\UserSelectionCategory;
use App\Model\LdapUser;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class SelectionListService
 * @package App\Service
 */
final class SelectionListService
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * HistoricService constructor.
     * @param EntityManager $entityManager
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        EntityManager $entityManager,
        TokenStorageInterface $tokenStorage
    ) {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return array|UserSelectionCategory[]
     */
    public function getCategories(): array
    {
        if (
            $this->tokenStorage->getToken() instanceof TokenInterface &&
            $this->tokenStorage->getToken()->getUser() instanceof LdapUser
        ) {
            return $this->entityManager->getRepository(UserSelectionCategory::class)->findBy(
                ['user_uid' => $this->tokenStorage->getToken()->getUser()->getUid()]
            );
        }

        return $this->entityManager->getRepository(UserSelectionCategory::class)->findAll();
    }

    /**
     * @param string $title
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createCategory(string $title): void
    {
        $selectionCategory = new UserSelectionCategory($this->tokenStorage->getToken()->getUser(), $title);
        $this->entityManager->persist($selectionCategory);
        $this->entityManager->flush();
    }

    /**
     * @param UserSelectionCategory $category
     * @param $title
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateCategory(UserSelectionCategory $category, $title)
    {
        $category->setTitle($title);
        $this->entityManager->flush();
    }
}
