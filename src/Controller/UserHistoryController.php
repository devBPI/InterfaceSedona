<?php


namespace App\Controller;

use App\Service\HistoricService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/historique", name="user_historic")
 *
 * Class UserHistoryController
 * @package App\Controller
 */
class UserHistoryController extends AbstractController
{
    const INPUT_NAME = 'history';

    /**
     * @var HistoricService
     */
    private $historicService;

    /**
     * UserHistoryController constructor.
     * @param HistoricService $historicService
     */
    public function __construct(HistoricService $historicService)
    {
        $this->historicService = $historicService;
    }

    /**
     * @Route("/", methods={"GET","POST"}, name="_index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function historicAction(Request $request)
    {
        if (count($request->request->all()) > 0) {
            $listObj = $request->get(self::INPUT_NAME);
            $action = $request->get('action');

            $this->historicService->applyAction($action, $listObj);
        }

        return $this->render('user/historic.html.twig', [
            'histories' => $this->historicService->getHistory()
        ]);
    }

}
