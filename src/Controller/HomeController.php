<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Thematic;
use App\Service\Provider\CarouselProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 */
final class HomeController extends AbstractController
{
    /**
     * @var CarouselProvider
     */
    private CarouselProvider $carouselProvider;

    /**
     * HomeController constructor.
     * @param CarouselProvider $carouselProvider
     */
    public function __construct(CarouselProvider $carouselProvider)
    {
        $this->carouselProvider = $carouselProvider;
    }

    /**
     * @Route("", methods={"GET","HEAD"}, name="home")
     * @Route("/", methods={"GET","HEAD"}, name="home2")
     */
    public function indexAction(): Response
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





	if (isset($_SESSION['access_token']))
	{
		echo "<span>access_token : </span>";
		print_r($_SESSION['access_token']);
		echo "<br />";
	}



	if(isset($_GET['code']) && isset($_GET['session_state']))
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
