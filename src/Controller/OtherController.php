<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OtherController extends AbstractController
{

    /**
     * @Route("/service", methods={"GET","HEAD"}, name="other_service")
     */
    public function bibliographicRecordAction(Request $request)
    {
        return $this->render('other/service.html.twig', []);
    }

}
