<?php


namespace App\Controller;


use App\Model\SearchResult;
use App\Service\Provider\AuthorProvider;
use App\Service\Provider\NoticeProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController
 * @package App\Controller
 */
class SearchController extends AbstractController
{
    /**
     * @var AuthorProvider
     */
    private $authorProvider;
    /**
     * @var NoticeProvider
     */
    private $noticeProvider;

    /**
     * SearchController constructor.
     * @param AuthorProvider $authorProvider
     * @param NoticeProvider $noticeProvider
     */
    public function __construct(AuthorProvider $authorProvider, NoticeProvider $noticeProvider)
    {
        $this->authorProvider = $authorProvider;
        $this->noticeProvider = $noticeProvider;
    }

    /**
     * @Route("/recherche", name="search")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $objSearch = new SearchResult($request->get('search', ''));
        if ($objSearch->hasQuery()) {
            $objSearch
                ->setAuthors(
                    $this->authorProvider->getListBySearch($objSearch->getQuery())
                )
                ->setNotices(
                    $this->noticeProvider->getListBySearch($objSearch->getQuery())
                )
                ->setOnlineNotices(
                    $this->noticeProvider->getListOnlineBySearch($objSearch->getQuery())
                );
        }

        return $this->render('search/index.html.twig', [
            'toolbar'=> 'search',
            'objSearch' => $objSearch
        ]);
    }

    /**
     * @Route("/recherche-avancer", name="search_advanced")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function advancedSearchAction(Request $request)
    {
        return $this->render('search/advanced-search.html.twig', []);
    }

    /**
     * @Route("/autocompletion", name="search_autocompletion")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function autocompletionAction(Request $request)
    {
        return $this->render('search/autocompletion.html.twig', []);
    }

}
