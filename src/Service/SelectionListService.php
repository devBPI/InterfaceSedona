<?php
declare(strict_types=1);

namespace App\Service;

use App\Controller\UserSelectionController;
use App\Entity\UserSelectionCategory;
use App\Entity\UserSelectionDocument;
use App\Model\Exception\SelectionCategoryException;
use App\Model\LdapUser;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
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
     * @param array|null $ids
     * @return array|UserSelectionCategory[]
     */
    public function getCategories(array $ids = []): array
    {
        if (
            $this->tokenStorage->getToken() instanceof TokenInterface &&
            $this->tokenStorage->getToken()->getUser() instanceof LdapUser
        ) {
            $param = ['user_uid' => $this->tokenStorage->getToken()->getUser()->getUid()];
            if (count($ids) > 0) {
                $param += ['id' => $ids];
            }
            return $this->entityManager->getRepository(UserSelectionCategory::class)->findBy(
                $param
            );
        }

        return $this->entityManager->getRepository(UserSelectionCategory::class)->findAll();
    }

    /**
     * @param string $title
     * @param bool $flush
     * @return UserSelectionCategory
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createCategory(string $title, $flush = true): UserSelectionCategory
    {
        $selectionCategory = new UserSelectionCategory($this->tokenStorage->getToken()->getUser(), $title);
        $this->entityManager->persist($selectionCategory);
        if ($flush) {
            $this->entityManager->flush();
        }

        return $selectionCategory;
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

    /**
     * @param Request $request
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addDocumentToCategories(Request $request)
    {
        $documents = [];
        foreach ($request->get('item', []) as $item) {
            $documents[] = new UserSelectionDocument($item);
        }

        foreach ($this->getCategoriesByRequest($request) as $userSelectionCategory) {
            foreach ($documents as $document) {
                $userSelectionCategory->addDocument($document);
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function getCategoriesByRequest(Request $request): array
    {
        $categories = [];

        $categoryIds = $request->get(UserSelectionController::INPUT_CATEGORY, []);
//        dump($categoryIds);
        if (count($categoryIds) > 0) {
            $categories = $this->getCategories($categoryIds);
        }

        if ($request->get('add_new_category', false) === '1') {
            $title = $request->get('new_category_title', null);
            if (empty($title)) {
                throw new SelectionCategoryException('modal.list-create.mandatory-field');
            }

            $categories[] = $this->createCategory($title, false);
        }

        if (count($categories) === 0) {
            throw new SelectionCategoryException('modal.list-add.message-error.no-category');
        }

        return $categories;
    }
}
