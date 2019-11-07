<?php


namespace App\Controller;

use App\Service\HistoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/historique", name="user_history")
 *
 * Class UserHistoryController
 * @package App\Controller
 */
final class UserHistoryController extends AbstractController
{
    const INPUT_NAME = 'history';

    /**
     * @var HistoryService
     */
    private $historyService;

    /**
     * UserHistoryController constructor.
     *
     * @param HistoryService $historyService
     */
    public function __construct(HistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    /**
     * @Route("/", methods={"GET","POST"}, name="_index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function indexAction(Request $request)
    {
        if (count($request->request->all()) > 0) {
            $listObj = $request->get(self::INPUT_NAME);
            $action = $request->get('action');

            $this->historyService->applyAction($action, $listObj);
        }

        return $this->render('user/history.html.twig', [
            'histories' => $this->historyService->getHistory()
        ]);
    }

}
