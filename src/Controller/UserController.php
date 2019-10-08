<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
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
}
