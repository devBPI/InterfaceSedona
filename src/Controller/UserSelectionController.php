<?php


namespace App\Controller;

use App\Entity\UserSelectionList;
use App\Entity\UserSelectionDocument;
use App\Model\Form\ExportNotice;
use App\Service\NoticeBuildFileService;
use App\Service\Provider\SearchProvider;
use App\Service\SelectionListService;
use App\Service\LoggerService;
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
     * @var NoticeBuildFileService
     */
    private $buildFileContent;

	public function __construct(
        SelectionListService $selectionListService,
        SearchProvider $searchProvider,
        NoticeBuildFileService $buildFileContent
    ){
		$this->selectionService = $selectionListService;
		$this->searchProvider = $searchProvider;
        $this->buildFileContent = $buildFileContent;
	}

    /**
     * @Route("/", methods={"GET","POST"}, name="_index")
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
                'printRoute'=> $this->generateUrl('user_selection_print', ['format' => ExportNotice::FORMAT_PDF]),
                'toolbar'   => UserSelectionDocument::class,
                'isNotice'  => false,
            ]
        );
    }


    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/list/creation", methods={"POST"}, name="_list_create")
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

            $param = [ 'error' => 'modal.list-create.mandatory-field'];
        }

        return $this->render('user/modal/creation-list-content.html.twig', $param);
    }

    /**
     * @Route("/list/ajout-documents", methods={"GET","POST"}, name="_list_add")
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
                $params = ['error' => $e->getMessage()];
            }
        }

        $params += ['lists' => $this->selectionService->getListsOfCurrentUser()];

        return $this->render('user/modal/add-list-content.html.twig', $params);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/list/{list}/modification", methods={"GET","POST"}, name="_list_edit")
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

            $param += ['error' => 'modal.list-create.mandatory-field'];
        }

        return $this->render('user/modal/edition-list-content.html.twig', $param);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/list/document/{document}/modification/commentaire", methods={"GET","POST"}, name="_list_document_comment_edit")
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
     * @Route("/list/check/{action}", methods={"GET","POST"}, name="_check_list_document")
     */
    public function checkListsPermalinks(Request $request, string $action): Response
    {
        $contents = json_decode($request->getContent(), true);
        $items = ['notices'=>[],'autorites'=> [], 'indices' => []];

        if($xml = $this->prepareXmlRequest($contents['notices'])){
            $items['notices'] = $this->selectionService->getPermalinks($this->searchProvider->CheckValidNoticePermalink($xml));
        }
        if($xml =  $this->prepareXmlRequest($contents['autorities'])){
            $items['autorites'] = $this->selectionService->getPermalinks($this->searchProvider->CheckValidAuthorityPermalink($xml));
        }
        if( $xml = $this->prepareXmlRequest($contents['indices'])){
            $items['indices'] = $this->selectionService->getPermalinks($this->searchProvider->CheckValidIndicePermalink($xml));
        }
        $listPermalinkNotice =  array_unique(array_values(array_merge($items['notices'],$items['autorites'],$items['indices'])));

        $request->getSession()->set('ItemsNotAvailable', json_encode($items)); //Important de se trouver avant les returns pour le capter dans src/Service/NoticeBuildFileService.php->getNoticeWrapper

        if (count($listPermalinkNotice)===0 || (count($listPermalinkNotice) === 1 && empty($listPermalinkNotice[0])) ){
            return new Response ("", Response::HTTP_NO_CONTENT);
        }

        return $this->render('user/modal/check-permalink-list-success.html.twig',
            $this->selectionService->getSelectionOfobjectByPermalinks($listPermalinkNotice)+['action'=> $action]
        );
    }

    /**
     * @Route("/print/selection.{format}", methods={"GET","HEAD"}, name="_print", requirements={"format" = "html|pdf|txt"}, defaults={"format" = "pdf"})
     */
    public function printAction(Request $request) :Response
    {
        $sendAttachement = ExportNotice::createFromRequest($request)
            ->setNotices($request->get('notices'))
            ->setAuthorities($request->get('authorities'))
            ->setIndices($request->get('indices'))
        ;

        return $this->buildFileContent->buildFile($sendAttachement, UserSelectionDocument::class);
    }

    /**
     * @param array<string>|null $payload
     * @return string
     */
    private function prepareXmlRequest(?array $payload ) :string
    {
        if (empty($payload) || !is_array($payload)) {
            return "";
        }

        $list = [];
        foreach ($payload as  $item){
            $list[]=sprintf('<string>%s</string>', $item);
        }

        return '<list>'.implode(' ', $list).'</list>';
    }
}
