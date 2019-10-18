<?php


namespace App\Controller;

use App\Entity\UserSelectionList;
use App\Entity\UserSelectionDocument;
use App\Model\Form\ExportNotice;
use App\Service\NoticeBuildFileService;
use App\Service\SelectionListService;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
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
class UserSelectionController extends AbstractController
{
    const INPUT_NAME = 'selection';
    const INPUT_LIST = 'list';
    const CHECK_NEW_LIST = 'new_list';
    const INPUT_LIST_TITLE = 'selection_list_title';
    const INPUT_DOCUMENT = 'document';
    const INPUT_DOC_COMMENT = 'selection_document_comment';

    /**
     * @var SelectionListService
     */
    private $selectionService;
    /**
     * @var NoticeBuildFileService
     */
    private $buildFileContent;

    /**
     * UserSelectionController constructor.
     * @param SelectionListService $selectionListService
     * @param NoticeBuildFileService $buildFileContent
     */
    public function __construct(SelectionListService $selectionListService)
    {
        $this->selectionService = $selectionListService;
    }

    /**
     * @Route("/", methods={"GET","POST"}, name="_index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function selectionAction(Request $request, SessionInterface $session): Response
    {
      //  $session->remove('selection_session');
        if (count($request->request->all()) > 0) {
            $listObj = $request->get(self::INPUT_NAME, []);
            $action = $request->get('action');
            $this->selectionService->applyAction($action, $listObj);
        }

        return $this->render( 'user/selection.html.twig',
            $this->selectionService->getSelectionObjects() +[
                'printRoute'=> $this->generateUrl('selection_print', ['format' => 'pdf'])
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
        $params = [];
        if ($request->request->count() > 0) {
            try {
                $this->selectionService->addDocumentsToLists($request);

                return $this->render('user/modal/creation-list-success.html.twig', ['action' => 'add']);
            } catch (\Exception $e) {
                $params = [
                    'error' => $e->getMessage(),
                ];
            }
        }

        $params += [
            'lists' => $this->selectionService->getListsOfCurrentUser(),
            'object' => $request->get('current', null),
        ];

        return $this->render('user/modal/add-list-content.html.twig', $params);
    }

    /**
     * @Route("/list/ajout-documents-en-session", methods={"POST"}, name="_list_add_session", options={"expose"=true})
     * @param Request $request
     * @return Response
     */
    public function addDocumentListInSessionAction(Request $request): Response
    {
        $this->selectionService->addDocumentsInSession($request->get(self::INPUT_DOCUMENT, []));

        return new JsonResponse();
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



}
