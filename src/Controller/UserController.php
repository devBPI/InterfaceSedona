<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/authentification", methods={"GET","HEAD"}, name="user_login")
     */
    public function loginAction(Request $request)
    {
        return $this->render('user/login.html.twig', []);
    }

    /**
     * @Route("/compte", methods={"GET","HEAD"}, name="user_personal_data")
     */
    public function personalDataAction(Request $request)
    {
        return $this->render('user/personal-data.html.twig', []);
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
