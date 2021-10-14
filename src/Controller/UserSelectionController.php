<?php


namespace App\Controller;

use App\Entity\UserSelectionList;
use App\Entity\UserSelectionDocument;
use App\Service\Provider\SearchProvider;
use App\Service\SelectionListService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @route("/selection", name="user_selection")
 *
 * Class UserSelectionController
 * @package App\Controller
 */
final class UserSelectionController extends AbstractController
{
    const INPUT_NAME = 'selection';
    const INPUT_LIST = 'list';
    const CHECK_NEW_LIST = 'new_list';
    const INPUT_LIST_TITLE = 'selection_list_title';
    const INPUT_DOCUMENT = 'document';
    const INPUT_DOC_COMMENT = 'selection_document_comment';
    /**
     * @var SearchProvider
     */
    private $searchProvider;

    /**
     * @var SelectionListService
     */
    private $selectionService;

    /**
     * UserSelectionController constructor.
     * @param SelectionListService $selectionListService
     */
    public function __construct(SelectionListService $selectionListService, SearchProvider $searchProvider)
    {
        $this->selectionService = $selectionListService;
        $this->searchProvider = $searchProvider;
    }

    /**
     * @Route("/", methods={"GET","POST"}, name="_index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function selectionAction(Request $request): Response
    {
        if (count($request->request->all()) > 0) {
            $listObj    = $request->get(self::INPUT_NAME, []);
            $action     = $request->get('action');
            $this->selectionService->applyAction($action, $listObj);
        }

        return $this->render( 'user/selection.html.twig',
            $this->selectionService->getSelectionObjects() +[
                'printRoute'=> $this->generateUrl('selection_print', ['format' => 'pdf']),
                'toolbar'=> UserSelectionDocument::class
            ]
        );
    }


    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/list/creation", methods={"POST"}, name="_list_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createListAction(Request $request): Response
    {
        $param = [];
        $title = $request->get(self::INPUT_LIST_TITLE, null);
        if ($title !== null) {
            if ($title !== '') {
                $this->selectionService->addDocumentsToLists($request);

                return $this->render('user/modal/creation-list-success.html.twig', [
                    'title' => $title,
                    'action' => 'create'
                ]);
            }

            $param = [
                'error' => 'modal.list-create.mandatory-field'
            ];
        }

        return $this->render('user/modal/creation-list-content.html.twig', $param);
    }

    /**
     * @Route("/list/ajout-documents", methods={"GET","POST"}, name="_list_add")
     * @param Request $request
     * @return Response
     */
    public function addListAction(Request $request): Response
    {
        $permalink = $request->get('permalink', null);
        $list= $request->get('list', []);

        if ($permalink == null){
            //from search
            if (count($request->get('document', [])) === 1){
                $document = $request->get('document', []);
                //if it's an action from Search, we just compare if we have only one item
                $permalink = $document[0]['id'];
            }
        }

        if ($this->selectionService->isSelected($permalink, $list)){
            return $this->render('user/modal/list-already-added-success.html.twig');
        }

        $params = [];

        if ($request->request->count() > 0) {
            try {
                $this->selectionService->addDocumentsToLists($request);

                return $this->render('user/modal/add-list-success.html.twig');
            } catch (\Exception $e) {
                $params = [
                    'error' => $e->getMessage(),
                ];
            }
        }

        $params += [
            'lists' => $this->selectionService->getListsOfCurrentUser()
        ];

        return $this->render('user/modal/add-list-content.html.twig', $params);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/list/{list}/modification", methods={"GET","POST"}, name="_list_edit")
     * @param UserSelectionList $list
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function editListAction(UserSelectionList $list, Request $request): Response
    {
        $param = [self::INPUT_LIST => $list];
        $title = $request->get(self::INPUT_LIST_TITLE, null);
        if ($title !== null) {
            if ($title !== '') {
                $this->selectionService->updateList($list, $title);

                return $this->render('user/modal/creation-list-success.html.twig', [
                    'title' => $title,
                    'action' => 'edit'
                ]);
            }

            $param += [
                'error' => 'modal.list-create.mandatory-field',
            ];
        }

        return $this->render('user/modal/edition-list-content.html.twig', $param);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/list/document/{document}/modification/commentaire", methods={"GET","POST"}, name="_list_document_comment_edit")
     * @param UserSelectionDocument $document
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function editDocumentCommentAction(UserSelectionDocument $document, Request $request): Response
    {
        $comment = $request->get(self::INPUT_DOC_COMMENT, null);
        if ($comment !== null) {

            $this->selectionService->updateDocument($document, $comment);
            return new Response('reload');
        }

        return $this->render('user/modal/comments-edit.html.twig', [
            'document' => $document
        ]);
    }


    /**
     * @Route("/list/check", methods={"GET","POST"}, name="_check_list_document")
     * @param Request $request
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function checkListsPermalinks(Request $request): JsonResponse
    {
        $contents = json_decode($request->getContent(), true);
        $items = [];
        $listPermalinkNotice = [];
        if($xml = $this->prepareXmlRequest($contents['notices'])){
            $listPermalinkNotice = $this->selectionService->getPermalinks($this->searchProvider->CheckValidNoticePermalink($xml));
            $items['notices'] = $listPermalinkNotice;
        }


        if($xml =  $this->prepareXmlRequest($contents['autorities'])){
            $items['autorites'] = $this->selectionService->getPermalinks($this->searchProvider->CheckValidAuthorityPermalink($xml));
            $listPermalinkNotice += $items['autorites'] ;
        }
        if( $xml = $this->prepareXmlRequest($contents['indices'])){
            $items['indices'] = $this->selectionService->getPermalinks($this->searchProvider->CheckValidIndicePermalink($xml));
            $listPermalinkNotice +=$items['indices'];
        }
        if (count($listPermalinkNotice)===0 || (count($listPermalinkNotice) === 1 && empty($listPermalinkNotice[0])) ){
            return new JsonResponse([
                    $request->get('action', 'export')]
            );
        }

        $request->getSession()->set('ItemsNotAvailable', \GuzzleHttp\json_encode($items));
        return new JsonResponse([
             $this->renderView('user/modal/check-permalink-list-success.html.twig',
                $this->selectionService->getSelectionOfobjectByPermalinks($listPermalinkNotice)+
                ['action'=>$request->get('action', 'export')]
            )]
        );
    }
    /**
     * @param $payload
     * @return string
     */
    private function prepareXmlRequest($payload )
    {
        $payload    = json_decode($payload, true);
        $list = [];
        $list[]='<list>';

        foreach ($payload as  $item){
            $list[]=sprintf('<string>%s</string>', $item);
        }
        $list[] = '</list>';

        return implode(' ', $list);
    }
}
