<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Thematic;
use App\Service\Provider\CarouselProvider;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Exception\XmlErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $attr = [];
        try {
            $attr['carousel'] = $this->carouselProvider->getHomeList();

        } catch (XmlErrorException $exception) {
        }
        $thematic = $this->getDoctrine()->getRepository(Thematic::class)->findAll();

        return $this->render(
            'home/default.html.twig',
            $attr+['thematic'=>$thematic]
        );
    }

    /**
     * @Route("/accueil/{thematic}", methods={"GET","HEAD"}, name="home_thematic", requirements={"theme"="autoformation|presse|cinema"})
     *
     * @param string $thematic
     * @return Response
     */
    public function thematicAction(string $thematic): Response
    {
        $attr = [
            'title' => $thematic
        ];
        try {
            $attr['carousel'] = $this->carouselProvider->getListByThematic($thematic);
        } catch (XmlErrorException $exception) {
        }

        $thematic = $this->getDoctrine()->getRepository(Thematic::class)->findOneBy(['slug'=>$thematic]);

        return $this->render(
            'home/thematic.html.twig',
            $attr + ['thematic'=>$thematic]
        );
    }
}
