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
        $carousel = $this->carouselProvider->getHomeList();
        $thematic = $this->getDoctrine()->getRepository(Thematic::class)->findAll();

        return $this->render('home/default.html.twig',[
                'carousel'  => $carousel,
                'thematic'  => $thematic
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
