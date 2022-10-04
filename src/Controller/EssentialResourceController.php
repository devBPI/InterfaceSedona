<?php

namespace App\Controller;

use App\Model\EssentialResource;
use App\Service\NavigationService;
use App\Service\NoticeBuildFileService;
use App\Service\Provider\EssentialsResourceProvider;
use App\Service\Provider\NoticeAuthorityProvider;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EssentialResourceController extends AbstractController
{
    /**
     * @var EssentialsResourceProvider
     */
    private EssentialsResourceProvider $essentialResourceProvider;

    public function __construct(EssentialsResourceProvider $essentialsResourceProvider) {
        $this->essentialResourceProvider = $essentialsResourceProvider;
    }
    /**
     * @Route("/essentials/{criteria}", methods={"GET"}, name="essential_resource")
     * @return Response
     */
    public function essentialResourceAction(string $criteria): Response
    {
        $criteria = 'AR-PRESSE-MEDIAS-EN-LIGNE';
        $resource = $this->essentialResourceProvider->getEssentialResource($criteria);
        return $this->render('essentials/index.html.twig', [
            'object'            => $resource
        ]);
    }
}