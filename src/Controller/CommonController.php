<?php


namespace App\Controller;

use App\Form\RepportErrorType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommonController extends AbstractController
{
    /**
     * @Route("signaler-une-erreur-sur-le-catalogue", name="common-repport-error")
     */
    public function reportErrorAction(Request $request)
    {
        $form = $this->createForm(RepportErrorType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->render('common/modal/report-error-success.html.twig', []);
        }
        return $this->render('common/modal/report-error-content.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
