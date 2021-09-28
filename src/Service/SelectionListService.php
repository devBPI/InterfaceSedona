<?php
declare(strict_types=1);

namespace App\Service;

use App\Controller\UserSelectionController;
use App\Entity\UserSelectionDocument;
use App\Entity\UserSelectionList;
use App\Model\Exception\SelectionCategoryException;
use App\Model\LdapUser;
use App\Model\PermalinksStatus;
use App\Model\PermalinkStatus;
use App\Repository\UserSelectionListRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class SelectionListService
 * @package App\Service
 */
final class SelectionListService extends AuthenticationService
{
    const SESSION_SELECTION_ID = 'selection_session';

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * SelectionListService constructor.
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
     * @param UserSelectionList $list
     * @param string $title
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateList(UserSelectionList $list, string $title)
    {
        $list->setTitle($title);
        $this->entityManager->flush();
    }

    /**
     * @param Request $request
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addDocumentsToLists(Request $request)
    {
        if (!$this->hasConnectedUser()) {
            $this->addDocumentsInSession($request->get(UserSelectionController::INPUT_DOCUMENT, []));
            return;
        }

        $documents = $this->createDocumentsFromRequest($request);

        foreach ($this->getListsFromRequest($request) as $userSelectionCategory) {
            foreach ($documents as $document) {
                /** @var UserSelectionDocument $document */
                // if element in document then
                if($this->entityManager
                    ->getRepository(UserSelectionList::class)
                    ->getList($this->getUser(), $document->getPermalink(), $userSelectionCategory)){

                    continue;
                }
                // endif
                $userSelectionCategory->addDocument(clone $document);
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @param Request $request
     * @return array
     */
    private function createDocumentsFromRequest(Request $request): array
    {
        $documents = [];

        foreach ($request->get(UserSelectionController::INPUT_DOCUMENT, []) as $item) {
            $documents[] = new UserSelectionDocument($item);
        }

        foreach ($request->get(UserSelectionController::INPUT_NAME, []) as $key => $param) {
            if ($key === UserSelectionController::INPUT_DOCUMENT) {
                foreach ($param as $item) {
                    $documents[] = $this->entityManager->getRepository(UserSelectionDocument::class)
                        ->find($item);
                }
            }
        }

        return $documents;
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    private function getListsFromRequest(Request $request): array
    {
        $lists = [];

        $listIds = $request->get(UserSelectionController::INPUT_LIST, []);
        if (count($listIds) > 0) {
            /** @var UserSelectionListRepository $userSelectionRepo */
            $userSelectionRepo = $this->entityManager->getRepository(UserSelectionList::class);

            $lists = $userSelectionRepo
                ->findByIds($this->getUser(), $listIds);
        }

        if ($request->get(UserSelectionController::CHECK_NEW_LIST, false) === '1') {
            $title = $request->get(UserSelectionController::INPUT_LIST_TITLE, null);
            if (empty($title)) {
                throw new SelectionCategoryException('modal.list-create.mandatory-field');
            }

            $lists[] = $this->createList($title);
        }

        if (count($lists) === 0) {
            throw new SelectionCategoryException('modal.list-add.message-error.no-list');
        }

        return $lists;
    }

    /**
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function saveDocumentsFromSession(): bool
    {
        if ($this->hasSession(self::SESSION_SELECTION_ID)) {
            $listTitle = UserSelectionList::DEFAULT_TITLE . ' '.date('d/m/Y H:i:s');

            $list = $this->createList($listTitle);

            foreach ($this->getSession(self::SESSION_SELECTION_ID) as $document) {
                $list->addDocument(new UserSelectionDocument($document));
            }

            $this->entityManager->persist($list);

            return true;
        }

        return false;
    }

    /**
     * @param LdapUser $user
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function getNextPosition(LdapUser $user): int
    {
        /** @var UserSelectionListRepository $userSelectionRepo */
        $userSelectionRepo = $this->entityManager->getRepository(UserSelectionList::class);

        return $userSelectionRepo->getMaxPositionOfUserList($user) + 1;
    }

    /**
     * @param string $title
     * @return UserSelectionList
     * @throws \Doctrine\ORM\ORMException
     */
    private function createList(string $title): UserSelectionList
    {
        $list = new UserSelectionList($this->getUser(), $title, $this->getNextPosition($this->getUser()));
        $this->entityManager->persist($list);

        return $list;
    }

    /**
     * @return array|UserSelectionList[]
     */
    public function getListsOfCurrentUser(): array
    {
        if ($this->hasConnectedUser()) {
            /** @var UserSelectionListRepository $userSelectionRepo */
            $userSelectionRepo = $this->entityManager->getRepository(UserSelectionList::class);

            return $userSelectionRepo
                ->findAllOrderedByPosition($this->getUser());
        }

        return [];
    }

    /**
     * @return array|UserSelectionList[]
     */
    public function getListsOfCurrentUserByPermalinks(array $permalinks): array
    {
        if ($this->hasConnectedUser()) {
            /** @var UserSelectionListRepository $userSelectionRepo */
            $userSelectionRepo = $this->entityManager->getRepository(UserSelectionList::class);

            return $userSelectionRepo
                ->findAllOrderedByPermalinks($this->getUser(), $permalinks);
        }

        return [];
    }


    /**
     * @param UserSelectionDocument $document
     * @param string $comment
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateDocument(UserSelectionDocument $document, string $comment)
    {
        $document->setComment($comment);
        $this->entityManager->flush();
    }

    /**
     * @param string $action
     * @param array $listObj
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function applyAction(string $action = null, array $listObj=[]): void
    {
        if ($action === 'delete') {
            if ($this->hasConnectedUser()) {
                $this->deleteListsAndDocuments($listObj);
            } elseif (array_key_exists(UserSelectionController::INPUT_DOCUMENT, $listObj)) {
                $this->deleteDocumentsFromSession($listObj[UserSelectionController::INPUT_DOCUMENT]);
            }
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
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function moveList(int $listId, bool $up = true): bool
    {
        $currentObj = $this->entityManager->getRepository(UserSelectionList::class)->find($listId);
        $currentPosition = $currentObj->getPosition();
        $current = [$currentObj];

        $allList = $this->getListsOfCurrentUser();

        $previous = array_filter(
            $allList,
            function (UserSelectionList $list) use ($currentPosition) {
                return $list->getPosition() < $currentPosition;
            }
        );
        $next = array_filter(
            $allList,
            function (UserSelectionList $list) use ($currentPosition) {
                return $list->getPosition() > $currentPosition;
            }
        );

        if ($up) {
            if (!is_array($previous) || count($previous) === 0) {
                return false;
            }

            $last = [end($previous)];
            array_pop($previous);
            $newList = array_merge($previous, $current, $last, $next);
        } else {
            if (!is_array($next) || count($next) === 0) {
                return false;
            }

            $first = [reset($next)];
            array_shift($next);
            $newList = array_merge($previous, $first, $current, $next);
        }

        foreach ($newList as $i => $list) {
            /** @var UserSelectionList $list */
            $list->setPosition($i);
        }

        $this->entityManager->flush();

        return true;
    }

    /**
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCountDocuments(): int
    {
        if ($this->hasConnectedUser()) {
            /** @var UserSelectionListRepository $userSelectionListRepo */
            $userSelectionListRepo = $this->entityManager->getRepository(UserSelectionList::class);
            return $userSelectionListRepo->getCountDocuments($this->getUser());
        }

        $docs = $this->getSession(self::SESSION_SELECTION_ID) ?? [];

        return count($docs);
    }

    /**
     * @return array
     */
    public function getSelectionObjects(): array
    {
        if ($this->hasConnectedUser()) {
            return ['lists' => $this->getListsOfCurrentUser()];
        }

        return ['documents' => $this->getDocumentsFromSession()];
    }


    public function getSelectionOfobjectByPermalinks(array $permalinks = []){
        if ($permalinks === []){
            return $this->getSelectionObjects();
        }

        if ($this->hasConnectedUser()) {
            return ['lists' => $this->getListsOfCurrentUserByPermalinks($permalinks)];
        }

        return ['documents' => $this->getDocumentsFromSessionByPermalink($permalinks)];


    }
    /**
     * @return array|UserSelectionDocument[]
     */
    public function getDocumentsFromSession(): array
    {

        return array_map(
            function ($document) {
                return new UserSelectionDocument($document);
            },
            $this->getSession(self::SESSION_SELECTION_ID)
        );
    }
    /**
     * @return array|UserSelectionDocument[]
     */
    public function getDocumentsFromSessionByPermalink(array $permalinks = []): array
    {
        if ($permalinks===[]){
            return [];
        }

        return array_map(
            function ($document) use ($permalinks) {
                if(in_array($document['id'], $permalinks)){
                   return new UserSelectionDocument($document);
                }
            },
            $this->getSession(self::SESSION_SELECTION_ID)
        );
    }

    /**
     * @param array $docIds
     */
    private function deleteDocumentsFromSession(array $docIds = []): void
    {
        $newDocs = array_filter(
            $this->session->get(self::SESSION_SELECTION_ID, []),
            function (array $doc) use ($docIds) {
                return !in_array($doc['id'], $docIds);
        });

        $this->setSession(self::SESSION_SELECTION_ID, $newDocs);
    }

    /**
     * @param array $documents
     */
    private function addDocumentsInSession(array $documents = [])
    {
        $this->appendSession(self::SESSION_SELECTION_ID, $documents);
    }


    /**
     * @param $permalink
     * @param array $listIds
     * @return bool
     */
    public function isSelected($permalink, $listIds=[])
    {
        if ($permalink===null){
            return false;
        }

        $list = $this->getSelectionObjects();
        // si on est en mode connecté
        if ($this->hasConnectedUser()){
            if ($listIds == [] || count($listIds)>1){
                return false;
            }
            $selection = $this->entityManager->getRepository(UserSelectionList::class)
                ->find($listIds[0]);

            $list =  $this->entityManager
                ->getRepository(UserSelectionList::class)
                ->getList($this->getUser(), $permalink, $selection);

            return $list!==0;
        }

        $list = array_filter($list['documents'], function (UserSelectionDocument $udocument) use ($permalink){
            return $udocument->getPermalink()===$permalink;
        });

        return count($list)>0;
    }

    /**
     * @param array $permalinks
     * @return \App\Entity\UserSelectionDocument[][]|\App\Entity\UserSelectionList[][]|array[]
     */
    public function getListByPermalinks(array $permalinks)
    {
        /**
         * @return array
         */

            if ($this->hasConnectedUser()) {
                return ['lists' => $this->getListsOfCurrentUser()];
            }

            return ['documents' => $this->getDocumentsFromSession()];
    }

    public function getPermalinks( PermalinksStatus $checkValidNoticePermalink)
    {
        return array_unique(array_map(
            function (PermalinkStatus $document){
                if (strtolower($document->getStatus())!=='found'){
                    return $document->getPermalink();
                }},
            $checkValidNoticePermalink->getPermalinkStatus()
        ));
    }
}
