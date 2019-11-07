<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class UserController
 * @package App\Controller
 */
final class UserController extends AbstractController
{
    /**
     * @Route("/authentification", methods={"GET","POST"}, name="user_login")
     * @param AuthenticationUtils $authUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(AuthenticationUtils $authUtils): Response
    {
        $authenticationError = $authUtils->getLastAuthenticationError();
        $attr = [];
        if ($authenticationError instanceof AuthenticationException) {
            $attr = [
                'error' => $authenticationError->getMessage(),
                'last_login' => $authUtils->getLastUsername()
            ];
        }

        return $this->render('user/login.html.twig', $attr);
    }

    /**
     * @Route("/compte", methods={"GET","HEAD"}, name="user_personal_data")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function personalDataAction(): Response
    {
        return $this->render('user/personal-data.html.twig');
    }
}
