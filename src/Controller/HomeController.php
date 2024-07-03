<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Thematic;
use App\Service\Provider\CarouselProvider;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class HomeController
 * @package App\Controller
 */
final class HomeController extends AbstractController
{
	private $session;

    /**
     * @var CarouselProvider
     */
    private CarouselProvider $carouselProvider;

    /**
     * HomeController constructor.
     * @param CarouselProvider $carouselProvider
     */
    public function __construct(CarouselProvider $carouselProvider, SessionInterface $session)
    {
        $this->carouselProvider = $carouselProvider;
		$this->session = $session;
    }

    /**
     * @Route("", methods={"GET","HEAD"}, name="home")
     * @Route("/", methods={"GET","HEAD"}, name="home2")
     */
    public function indexAction(Request $request, ClientRegistry $clientRegistry): Response
    {
        $carousel = $this->carouselProvider->getHomeList();
        $thematic = $this
            ->getDoctrine()
            ->getRepository(Thematic::class)
            ->findBy(
                [],
                ['title' => 'ASC']
            )
            ;
        ;


	$client = $clientRegistry->getClient('my_oauth2_client');
	if(isset($_GET['code']) && isset($_GET['session_state']))
	{
		try {
			// the exact class depends on which provider you're using
			$redirectUri = 'https://catalogue-dev.bpi.fr';
			$accessToken = $client->getAccessToken(['redirect_uri' => $redirectUri]);
			$this->session->set('oauth_access_token', $accessToken);

			// do something with all this new power!
			// e.g. $name = $user->getFirstName();
		} catch (IdentityProviderException $e) {
			// something went wrong!
			// probably you should return the reason to the user
			var_dump($e->getMessage()); die;
		}
		return $this->redirectToRoute('home');
	}


	if(null != ($this->session->get('oauth_access_token')))
	{
		$accessToken = $this->session->get('oauth_access_token');
		try
		{
			//echo "<span>access_token : </span>";
			$user = $client->fetchUserFromToken($accessToken);
			//var_dump($user);
			//echo "<br />email : ";
			//echo $user->toArray()['email'];
			//echo "<br />";
		}
		catch (\Exception $e)
		{
			//$this->session->remove('oauth_access_token');
			//return $this->redirectToRoute('home');
		}
	}

	if(false && isset($_SESSION['access_token']))
	{
		echo "<span>access_token : </span>";
		print_r($_SESSION['access_token']);
		echo "<br />";
	}



	if(false && isset($_GET['code']) && isset($_GET['session_state']))
	{

		$client_id = "ClientCatalogueBpiOIDC";
		$redirect_uri = "https://catalogue-dev.bpi.fr";
		$scope = "email openid profile address phone groupes RNConfirmed pseudo";
		$authorization_endpoint = "https://auth-test.bpi.fr/oauth2/authorize";

		$token_endpoint = "https://auth-test.bpi.fr/oauth2/token";
		$code = $_GET['code'];  // Le code récupéré après l'authentification

		$data = [
			'grant_type' => 'authorization_code',
			'code' => $code,
			'redirect_uri' => $redirect_uri,
			'client_id' => $client_id,
			'client_secret' => 'gfD524C44ahQKm',
		];
		/*echo "<span>data : </span>";
		print_r($data);
		echo "<br />";*/

		$options = [
			'http' => [
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data),
			],
		];
		/*echo "<span>options : </span>";
		print_r($options);
		echo "<br />";*/

		$context  = stream_context_create($options);
		/*echo "<span>context : </span>";
		print_r($context);
		echo "<br />";*/
		$response = file_get_contents($token_endpoint, false, $context);
		/*echo "<span>response : </span>";
		print_r($response);
		echo "<br />";
		echo "<span>access_token : </span>";*/
		$token_response = json_decode($response, true);
		$_SESSION['access_token'] = $token_response['access_token'];
		/*print_r($token_response['access_token']);
		echo "<br />";*/
		return $this->redirectToRoute('home');
		//header("Location: /");
	}


        return $this->render('home/default.html.twig',[
                'carousel'  => $carousel,
                'thematic'  => $thematic,
                'isNotice'  => false,
            ]);
    }

    /**
     * @Route("/{parcours}", methods={"GET","HEAD"}, name="home_thematic", requirements={"parcours"="autoformation|actualites-revues|cinema"})
     *
     * @param string $parcours
     * @return Response
     */
    public function thematicAction(string $parcours): Response
    {
        $carousel = $this->carouselProvider->getListByThematic($parcours);
        $object = $this->getDoctrine()->getRepository(Thematic::class)->findOneBy(['type'=>$parcours]);

        return $this->render('home/thematic.html.twig', [
                'title'     => $parcours,
                'thematic'  => $object,
                'carousel'  => $carousel,
            ]);
    }
}
