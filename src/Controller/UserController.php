<?php


namespace App\Controller;

use App\Service\HistoricService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @var HistoricService
     */
    private $historicService;

    /**
     * UserController constructor.
     * @param HistoricService $historicService
     */
    public function __construct(HistoricService $historicService)
    {
        $this->historicService = $historicService;
    }

    /**
     * @Route("/authentification", methods={"GET","POST"}, name="user_login")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction()
    {
        return $this->render('user/login.html.twig');
    }

    /**
     * @Route("/compte", methods={"GET","HEAD"}, name="user_personal_data")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function personalDataAction()
    {
        return $this->render('user/personal-data.html.twig');
    }

    /**
     * @Route("/selection", methods={"GET","HEAD"}, name="user_selection")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function selectionAction()
    {
        return $this->render('user/tabs/selection.html.twig', [
            'histories' => $this->historicService->getHistory()
        ]);
    }

    /**
     * @Route("/historique", methods={"GET","HEAD"}, name="user_historic")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function historicAction()
    {
        return $this->render('user/tabs/historic.html.twig', [
            'histories' => $this->historicService->getHistory()
        ]);
    }

    /**
     * @Route("/list/cree", methods={"GET","HEAD"}, name="user_list_add")
     */
    public function addListAction(Request $request)
    {
        return $this->render('user/modal/list_add.html.twig', []);
    }

    /**
     * @Route("/list/modifier", methods={"GET","HEAD"}, name="user_list_edit")
     */
    public function editListAction(Request $request)
    {
        return $this->render('user/modal/list_edit.html.twig', []);
    }

    /**
     * @Route("/list/supprimer", methods={"GET","HEAD"}, name="user_list_remove")
     */
    public function removeListAction(Request $request)
    {
        return $this->render('user/modal/list_remove.html.twig', []);
    }

}
