<?php


namespace App\Controller;

use App\Form\RepportErrorType;
use App\Model\From\ReportError;
use App\Service\MailSenderService;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommonController extends AbstractController
{
    /**
     * @Route("signaler-une-erreur-sur-le-catalogue", name="common-repport-error")
     */
    public function reportErrorAction(Request $request, MailSenderService $mailSenderService)
    {
        $form = $this->createForm(RepportErrorType::class, new ReportError());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repportError = $form->getData();

            $fromEmail = empty($repportError->getEmail()) ? 'cataloge@sedona.fr' : $repportError->getEmail();
            if ($mailSenderService->sendMail(
                'common/modal/report-error.email.twig',
                ['data'=> $repportError],
                $fromEmail,
                'sender@sedona.fr'
            )) {
                return $this->render('common/modal/report-error-success.html.twig');
            } else {
                $form->addError(new FormError("Une erreurs est suvenue lors de l'envoie de l'email \n veuillez resseyer plustard"));
            }
        }
        return $this->render('common/modal/report-error-content.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
