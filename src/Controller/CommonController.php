<?php

namespace App\Controller;

use App\Form\RepportErrorType;
use App\Form\ExportNoticeType;
use App\Form\ShareByMailType;
use App\Form\SuggestByMailType;
use App\Model\From\ReportError;
use App\Model\From\ExportNotice;
use App\Model\From\ShareByMail;
use App\Model\From\SuggestByMail;
use App\Service\MailSenderService;
use http\Exception\InvalidArgumentException;
use spec\Behat\MinkExtension\ServiceContainer\Driver\SeleniumFactorySpec;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommonController
 * @package App\Controller
 */
class CommonController extends AbstractController
{

    /**
     * @Route("signaler-une-erreur-sur-le-catalogue", name="common-repport-error")
     * @param Request $request
     * @param MailSenderService $mailSenderService
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    public function reportErrorAction(Request $request, MailSenderService $mailSenderService)
    {
        $form = $this->createForm(RepportErrorType::class, new ReportError());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repportError = $form->getData();

            $fromEmail = empty($repportError->getEmail()) ? 'cataloge@sedona.fr' : $repportError->getEmail();
            if ($mailSenderService->sendMail(
                'common/modal/content.email.twig', ['data' => $repportError], $fromEmail, 'sender@sedona.fr'
            )) {
                return $this->render('common/modal/report-error-success.html.twig');
            } else {
                $form->addError(
                    new FormError("Une erreur est survenue lors de l'envoie de l'e-mail \n veuillez reessayer plus tard SVP.")
                );
            }
        }

        return $this->render(
            'common/modal/report-error-content.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/share-by-mail", name="share_by_mail")
     * @param Request $request
     * @param MailSenderService $mailSenderService
     * @return Response
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    public function shareByMailAction(Request $request, MailSenderService $mailSenderService): Response
    {
        $form = $this->createForm(ShareByMailType::class, new ShareByMail());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /** @var ShareByMail $object */
            $object = $form->getData();
            if ($mailSenderService->sendMail(
                'common/modal/content.email.twig',
                ['data' => $object],
                'no-reply@sedona.fr',
                $object->getReciever(), $object->getSender()
            )) {
                return $this->render('common/modal/share-success.html.twig');
            } else {
                $form->addError(
                    new FormError("Une erreur est survenue lors de l'envoie de l'e-mail \n veuillez reessayer plus tard SVP.")
                );
            }
        }

        return $this->render(
            'common/modal/share-content.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/suggest-by-mail", name="suggest_by_mail")
     * @param Request $request
     * @param MailSenderService $mailSenderService
     * @return Response
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    public function suggestByMailAction(Request $request, MailSenderService $mailSenderService): Response
    {
        $form = $this->createForm(SuggestByMailType::class, new SuggestByMail());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $object = $form->getData();
            if ($mailSenderService->sendMail(
                'common/modal/suggestion-content.email.twig',
                ['data' => $object],
                'no-reply@sedona.fr',
                'catalogue.public@bpi.fr'
            )) {
                return $this->render('common/modal/share-success.html.twig');
            } else {
                $form->addError(
                    new FormError("Une erreur est survenue lors de l'envoie de l'e-mail \n veuillez reessayer plus tard SVP.")
                );
            }
        }

        return $this->render(
            'common/modal/suggestion-content.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

}
