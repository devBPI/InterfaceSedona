<?php

namespace App\Controller;

use App\Entity\UserSelectionDocument;
use App\Form\ExportNoticeType;
use App\Form\ReportErrorPageType;
use App\Form\ReportErrorType;
use App\Form\ShareByMailType;
use App\Form\SuggestByMailType;
use App\Model\Form\ExportNotice;
use App\Model\Form\ReportError;
use App\Model\Form\ShareByMail;
use App\Model\Form\SuggestByMail;
use App\Model\Notice;
use App\Service\MailSenderService;
use App\Service\NoticeBuildFileService;
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
     * @var NoticeBuildFileService
     *
     */
    private $noticeBuildFileService;
    /**
     * ReportingController constructor.
     * @param MailSenderService $mailSenderService
     */
    public function __construct( MailSenderService $mailSenderService, NoticeBuildFileService  $noticeBuildFileService)
    {

        $this->mailSenderService = $mailSenderService;
        $this->noticeBuildFileService = $noticeBuildFileService;
    }

    /**
     * @Route("/signaler-une-erreur-sur-le-catalogue/", name="common-report-error")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    public function reportErrorAction(Request $request, string $permalink = null): Response
    {
        $form = $this->createForm(ReportErrorType::class, new ReportError());
        $form->handleRequest($request);
        $request->getUri();
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ReportError $reportData */
            $reportData = $form->getData();

            if ($this->mailSenderService->sendEmail(
                'common/modal/content_error.email.twig',
                ['data' => $reportData],
                null,
                null,
                $reportData->getEmail(),
		null
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
                'permalink' => $permalink
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
            echo($reportError->getEmail());

            if ($this->mailSenderService->sendEmail(
                'common/modal/content.email.twig',
                ['data' => $reportError],
                null,
                null,
                $reportError->getEmail(),
		null
            )) {
                return $this->render('common/error-success.html.twig');
            }

            $form->addError(
                new FormError("Une erreur est survenue lors de l'envoie de l'e-mail \n veuillez reessayer plus tard SVP.")
            );
        }

        $renderPage = $request->get('renderPage', true) == true;
        return $this->render($renderPage ? 'common/error-form.html.twig' : 'common/error-form-modal.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/share-by-mail/", name="share_by_mail")
     * @param Request $request
     * @return Response
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    public function shareByMailAction(Request $request): Response
    {
        $form = $this->createForm(ExportNoticeType::class, new ExportNotice());
        $form->handleRequest($request);
        $link = $request->get('link');
        if ($form->getData() instanceof  ShareByMail && ($dataLink =  $form->getData()->getLink())!==null) {
            $link = $dataLink;
        }
        if ($form->isSubmitted() && $form->isValid()){
            /** @var ExportNotice $object */
            $object = $form->getData();
            $content = $this->noticeBuildFileService->buildFile($object, UserSelectionDocument::class);
            dump($object);
            if ($this->mailSenderService->sendEmail(
                'common/modal/content.email.twig',
                ['data' => $object, 'content' => $content],
                null,
                $object->getReciever(),
                $object->getSender(),
                null
            )) {
                return $this->render('common/modal/share-success.html.twig');
            } else {
                $form->addError(
                    new FormError("Une erreur est survenue lors de l'envoi de l'e-mail \n veuillez réessayer plus tard SVP.")
                );
            }
        }

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
            /** @var SuggestByMail $object */
            $object = $form->getData();
            if ($this->mailSenderService->sendEmail(
                'common/modal/suggestion-content.email.twig',
                ['data' => $object],
                null,
                $this->mailSenderService->getSenderForSuggestion(),
                $object->getEmail(),
                null
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
