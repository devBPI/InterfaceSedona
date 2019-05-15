<?php

namespace App\Controller;

use App\Service\Provider\CarouselProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @Route("", name="home")
     * @Route("/", name="home2")
     */
    public function indexAction(Request $request)
    {
        try {
            $param = [
                'carousel' => $this->carouselProvider->getHomeList()
            ];
        } catch (\Exception $exception) {
            $param = [
                'error' => $exception->getMessage()
            ];
        }

        dump($param);

        return $this->render('home/default.html.twig', $param);
    }

    /**
     * @Route("/accueille/auto-formation", name="home_autoformation")
     * @Route("/accueille/presse", name="home_presse")
     * @Route("/accueille/cinema", name="home_cinema")
     */
    public function thematicAction(Request $request)
    {
        return $this->render('home/thematic.html.twig', []);
    }
}
