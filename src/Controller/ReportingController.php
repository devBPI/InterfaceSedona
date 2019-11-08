<?php

namespace App\Controller;

use App\Form\ReportErrorPageType;
use App\Form\ReportErrorType;
use App\Form\ShareByMailType;
use App\Form\SuggestByMailType;
use App\Model\Form\ReportError;
use App\Model\Form\ShareByMail;
use App\Model\Form\SuggestByMail;
use App\Service\MailSenderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ReportingController
 * @package App\Controller
 */
final class ReportingController extends AbstractController
{
    /**
     * @var MailSenderService
     */
    private $mailSenderService;

    /**
     * ReportingController constructor.
     * @param MailSenderService $mailSenderService
     */
    public function __construct( MailSenderService $mailSenderService)
    {

        $this->mailSenderService = $mailSenderService;
    }

    /**
     * @Route("/signaler-une-erreur-sur-le-catalogue", name="common-report-error")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    public function reportErrorAction(Request $request): Response
    {
        $form = $this->createForm(ReportErrorType::class, new ReportError());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repportError = $form->getData();

            $fromEmail = empty($repportError->getEmail()) ? 'cataloge@sedona.fr' : $repportError->getEmail();
            if ($this->mailSenderService->sendMail(
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
     * @Route("/page-error-reporting", name="common-report-error-page")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    public function reportErrorPageAction(Request $request): Response
    {
        $form = $this->createForm(ReportErrorPageType::class, new ReportError());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reportError = $form->getData();

            $fromEmail = empty($reportError->getEmail()) ? 'cataloge@sedona.fr' : $reportError->getEmail();
            if ($this->mailSenderService->sendMail(
                'common/modal/content.email.twig', ['data' => $reportError], $fromEmail, 'catalogue.public@bpi.fr'
            )) {
                return $this->render('common/error-success.html.twig');
            }

            $form->addError(
                new FormError("Une erreur est survenue lors de l'envoie de l'e-mail \n veuillez reessayer plus tard SVP.")
            );
        }

        return $this->render('common/error-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/share-by-mail", name="share_by_mail")
     * @param Request $request
     * @return Response
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    public function shareByMailAction(Request $request): Response
    {
        $form = $this->createForm(ShareByMailType::class, new ShareByMail());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            /** @var ShareByMail $object */
            $object = $form->getData();
            if ($this->mailSenderService->sendMail(
                'common/modal/content.email.twig',
                ['data' => $object],
                'no-reply@sedona.fr',
                $object->getReceiver(), $object->getSender()
            )) {
                return $this->render('common/modal/share-success.html.twig');
            } else {
                $form->addError(
                    new FormError("Une erreur est survenue lors de l'envoi de l'e-mail \n veuillez réessayer plus tard SVP.")
                );
            }
        }
        $link = $request->get('link');

        return $this->render(
            'common/modal/share-content.html.twig',
            [
                'form' => $form->createView(),
                'link' => $link
            ]
        );
    }

    /**
     * @Route("/suggest-by-mail", name="suggest_by_mail")
     * @param Request $request
     * @return Response
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    public function suggestByMailAction(Request $request): Response
    {
        $form = $this->createForm(SuggestByMailType::class, new SuggestByMail());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $object = $form->getData();
            if ($this->mailSenderService->sendMail(
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