<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    const SECURITY_REFERER = '_security_referer';

    /**
     * @Route("/authentification", methods={"GET","POST"}, name="user_login")
     * @param Request $request
     * @param AuthenticationUtils $authUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils): Response
    {
        $attr = [];
        $authenticationError = $authUtils->getLastAuthenticationError();
        if ($authenticationError instanceof AuthenticationException) {
            $attr = [
                'error' => $authenticationError->getMessage(),
                'last_login' => $authUtils->getLastUsername()
            ];
            if (empty($request->get("_username"))) {
                $attr['error_username'] = "_username.empty";
            } elseif (!preg_match('/^.+\@\S+\.\S+$/', $request->get("_username"))) {
                // voir Symfony\Component\Validator\Constraints\EmailValidator
                $attr['error_username'] = "email.format";
            }
            if (empty($request->get("_password"))) {
                $attr['error_password'] = "_password.empty";
            }
        }

        if ($request->headers->get('referer') !== $request->getUri()) {
            $request->getSession()->set(self::SECURITY_REFERER, $request->headers->get('referer'));
        }
        $attr['referer'] = $request->getSession()->get(self::SECURITY_REFERER);

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
