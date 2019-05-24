<?php


namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OtherController extends AbstractController
{

    /**
     * @Route("/service", name="other_service")
     * @Method("GET")
     */
    public function bibliographicRecordAction(Request $request)
    {
        return $this->render('other/service.html.twig', []);
    }

}
