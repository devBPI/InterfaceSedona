<?php
declare(strict_types=1);

namespace App\Service;

use App\Controller\UserSelectionController;
use App\Entity\UserSelectionDocument;
use App\Entity\UserSelectionList;
use App\Model\Exception\SelectionCategoryException;
use App\Model\LdapUser;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
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
     * @var null|\Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    private $session;

    /**
     * HistoricService constructor.
     * @param EntityManager $entityManager
     * @param TokenStorageInterface $tokenStorage
     * @param RequestStack $requestStack
     */
    public function __construct(
        EntityManager $entityManager,
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack
    ) {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->session = $requestStack->getMasterRequest()->getSession();
    }

    /**
     * @param array|null $ids
     * @return array|UserSelectionList[]
     */
    public function getLists(array $ids = []): array
    {
        if (
            $this->tokenStorage->getToken() instanceof TokenInterface &&
            $this->tokenStorage->getToken()->getUser() instanceof LdapUser
        ) {
            $uid = $this->tokenStorage->getToken()->getUser()->getUid();
        } else {
            $uid = $this->session->getId();
        }

        if (count($ids) > 0) {
            return $this->entityManager->getRepository(UserSelectionList::class)->findByIds($ids);
        }

        return $this->entityManager->getRepository(UserSelectionList::class)->findAllOrderedByPosition($uid);
    }

    /**
     * @param string $title
     * @param bool $flush
     * @return UserSelectionList
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createCategory(string $title, $flush = true): UserSelectionList
    {
        $selectionCategory = new UserSelectionList($this->tokenStorage->getToken()->getUser(), $title);
        $this->entityManager->persist($selectionCategory);
        if ($flush) {
            $this->entityManager->flush();
        }

        return $selectionCategory;
    }

    /**
     * @param UserSelectionList $category
     * @param $title
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateCategory(UserSelectionList $category, $title)
    {
        $category->setTitle($title);
        $this->entityManager->flush();
    }

    /**
     * @param Request $request
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addDocumentToLists(Request $request)
    {
        $documents = [];
        foreach ($request->get(UserSelectionController::INPUT_DOCUMENT, []) as $item) {
            $documents[] = new UserSelectionDocument($item);
        }

        foreach ($this->getListsByRequest($request) as $userSelectionCategory) {
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
    private function getListsByRequest(Request $request): array
    {
        $lists = [];

        $listIds = $request->get(UserSelectionController::INPUT_LIST, []);
        if (count($listIds) > 0) {
            $lists = $this->getLists($listIds);
        }

        if ($request->get(UserSelectionController::CHECK_NEW_LIST, false) === '1') {
            $title = $request->get(UserSelectionController::INPUT_LIST_TITLE, null);
            if (empty($title)) {
                throw new SelectionCategoryException('modal.list-create.mandatory-field');
            }

            $lists[] = $this->createCategory($title, false);
        }

        if (count($lists) === 0) {
            throw new SelectionCategoryException('modal.list-add.message-error.no-category');
        }

        return $lists;
    }

    /**
     * @param UserSelectionDocument $document
     * @param $comment
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateDocument(UserSelectionDocument $document, $comment)
    {
        $document->setComment($comment);
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
            $this->deleteListsAndDocuments($listObj);
        }

        if ($action === 'moveUp') {
            $this->moveList((int)$listObj[UserSelectionController::INPUT_LIST][0]);
        }
        if ($action === 'moveDown') {
            $this->moveList((int)$listObj[UserSelectionController::INPUT_LIST][0], false);
        }
    }

    /**
     * @param array $listObj
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function deleteListsAndDocuments(array $listObj = []): void
    {
        if (array_key_exists(UserSelectionController::INPUT_DOCUMENT, $listObj)) {
            foreach ($listObj[UserSelectionController::INPUT_DOCUMENT] as $docId) {
                $document = $this->entityManager->getRepository(UserSelectionDocument::class)->find($docId);
                if ($document instanceof UserSelectionDocument) {
                    $this->entityManager->remove($document);
                }
            }
        }

        if (array_key_exists(UserSelectionController::INPUT_LIST, $listObj)) {
            foreach ($listObj[UserSelectionController::INPUT_LIST] as $listId) {
                $list = $this->entityManager->getRepository(UserSelectionList::class)->find($listId);
                if ($list instanceof UserSelectionList) {
                    $this->entityManager->remove($list);
                }
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @param int $listId
     * @param bool $up
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function moveList(int $listId, bool $up = true)
    {
        $currentObj = $this->entityManager->getRepository(UserSelectionList::class)->find($listId);
        $currentPosition = $currentObj->getPosition();
        $current = [$currentObj];

        $allList = $this->getLists();

        $previous = array_filter($allList, function ($list) use ($currentPosition) {
            return $list->getPosition() < $currentPosition;
        });
        $next = array_filter($allList, function ($list) use ($currentPosition) {
            return $list->getPosition() > $currentPosition;
        });

        if ($up) {
            $last = [end($previous)];
            array_pop($previous);
            $newList = array_merge($previous, $current, $last, $next);
        } else {
            $first = [reset($next)];
            array_shift($next);
            $newList = array_merge($previous, $first, $current, $next);
        }

        foreach ($newList as $i => $list) {
            $list->setPosition($i);
        }

        $this->entityManager->flush();
    }
}
