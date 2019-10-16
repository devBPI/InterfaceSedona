<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MediaController
 * @package App\Controller
 */
class MediaController extends AbstractController
{
    /**
     * @Route("media/{permalink}", name="media-link", requirements={"permalink"=".+"})
     * @param string $permalink
     * @return Response
     */
    public function displayMediaLinkAction(string $permalink): Response
    {
        if(empty($permalink)) {
            throw new NotFoundHttpException('Missing text');
        }

        return new Response(
            "<media><id_objet>999999</id_objet><nom_bdm>".$permalink."</nom_bdm></media>",
            200,
            [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="launch.media"',
                'Cache-Control' => 'no-cache, must-revalidate'
            ]
        );
    }
}
