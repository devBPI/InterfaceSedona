<?php


namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class UserController
 * @package App\Controller
 */
final class UserController extends AbstractController
{
	const SECURITY_REFERER = '_security_referer';

	private $session;


	public function __construct(SessionInterface $session)
	{
		$this->session = $session;
	}

	/**
	 * @Route("/authentification_old", methods={"GET","POST"}, name="user_login_old")
	 * @param Request $request
	 * @param AuthenticationUtils $authUtils
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function loginActionOld(Request $request, AuthenticationUtils $authUtils): Response
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






		$client_id = "ClientCatalogueBpiOIDC";
		$redirect_uri = "https://catalogue-dev.bpi.fr";
		$scope = "email openid profile address phone groupes RNConfirmed pseudo";
		$authorization_endpoint = "https://auth-test.bpi.fr/oauth2/authorize";

		$params = http_build_query([
			'client_id' => $client_id,
			'redirect_uri' => $redirect_uri,
			'response_type' => 'code',
			'scope' => $scope,
		]);
		$authorization_url = "$authorization_endpoint?$params";
		$attr['auth_url'] = $authorization_url;





		return $this->render('user/login2.html.twig', $attr);
	}

	/**
	 * @Route("/connect", name="oauth_connect")
	 */
	public function connect(ClientRegistry $clientRegistry)
	{
		// Redirect to the "connect" route of the configured OAuth2 client
		$redirectUri = 'https://catalogue-dev.bpi.fr';
		return $clientRegistry
			->getClient('my_oauth2_client')
			->redirect(['email openid profile address phone groupes RNConfirmed pseudo'], ['redirect_uri' => $redirectUri]); // you can add scopes as the first argument
	}

	/**
	 * @Route("/connect/check", name="my_oauth2_check")
	 */
	public function connectCheck(Request $request, ClientRegistry $clientRegistry)
	{
		$client = $clientRegistry->getClient('my_oauth2_client');
		//$email = null;
		if(null != ($this->session->get('oauth_access_token')))
		{
			echo "<span>access_token : </span>";
			$accessToken = $this->session->get('oauth_access_token');
			$user = $client->fetchUserFromToken($accessToken);
			var_dump($user);
			//$email = $user->getEmail();
			echo "<br />";
		}

		return new Response();
	}

	/**
	 * @Route("/authentification2", methods={"GET","POST"}, name="user_logining")
	 * @param Request $request
	 * @param AuthenticationUtils $authUtils
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function login2Action(Request $request, AuthenticationUtils $authUtils): Response
	{
		return $this->redirectToRoute('home');
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
