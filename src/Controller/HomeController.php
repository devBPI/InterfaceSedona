<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\Provider\CarouselProvider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
     * @var CarouselProvider
     */
    private $carouselProvider;

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
        return $this->render('home/default.html.twig', [
            'carousel' => $this->carouselProvider->getHomeList()
        ]);
    }

    /**
     * @Route("/accueil/autoformation", methods={"GET","HEAD"}, name="home_autoformation")
     * @Route("/accueil/presse", methods={"GET","HEAD"}, name="home_presse")
     * @Route("/accueil/cinema", methods={"GET","HEAD"}, name="home_cinema")
     *
     * @param Request $request
     * @return Response
     */
    public function thematicAction(Request $request): Response
    {
        $theme = substr($request->getPathInfo(), strrpos($request->getPathInfo(), '/') + 1);
        return $this->render('home/thematic.html.twig', [
            'title' => $theme,
            'carousel' => $this->carouselProvider->getListByThematic($theme)
        ]);
    }
}
