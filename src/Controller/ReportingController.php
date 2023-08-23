<?php

namespace App\Controller;

use App\Entity\UserSelectionDocument;
use App\Form\ReportErrorType;
use App\Form\SendByMailType;
use App\Form\SuggestByMailType;
use App\Model\Form\ReportError;
use App\Model\Form\SendByMail;
use App\Model\Form\SuggestByMail;
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
     */
    private $noticeBuildFileService;

    public function __construct( MailSenderService $mailSenderService, NoticeBuildFileService  $noticeBuildFileService)
    {
        $this->mailSenderService = $mailSenderService;
        $this->noticeBuildFileService = $noticeBuildFileService;
    }

    /**
     * @Route("/signaler-une-erreur-sur-le-catalogue/", name="common-report-error")
     */
    public function reportErrorAction(Request $request, string $permalink = null): Response
    {
        $form = $this->createForm(ReportErrorType::class, new ReportError());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ReportError $reportData */
            $reportData = $form->getData();
            if ($this->mailSenderService->sendEmail(
                'common/modal/report-error-content.email.twig',
                ['data' => $reportData],
                null,
                $reportData->getEmail()
            )) {
                return $this->render('common/modal/report-error-success.html.twig');
            } else {
                $form->addError(
                    new FormError("Une erreur est survenue lors de l'envoie de l'e-mail \n veuillez reessayer plus tard SVP.")
                );
            }
        }

        return $this->render('common/modal/report-error-content.html.twig', [
            'form'      => $form->createView(),
            'permalink' => $permalink
        ]);
    }

    /**
     * @Route("/send-by-mail", name="send_by_mail")
     */
    public function sendByMailAction(Request $request): Response
    {
        $form = $this->createForm( SendByMailType::class, new SendByMail());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            /** @var SendByMail $object */
            $object = $form->getData();
            $content = $this->noticeBuildFileService->buildContent($object, UserSelectionDocument::class);
            if ($this->mailSenderService->sendEmail(
                'common/modal/send-by-mail-content.email.twig',
                ['data' => $object, 'content' => $content],
                $object->getReciever(),
                $object->getSender()
            )) {
                return $this->render('common/modal/send-by-mail-success.html.twig');
            } else {
                $form->addError(
                    new FormError("Une erreur est survenue lors de l'envoi de l'e-mail \n veuillez réessayer plus tard SVP.")
                );
            }
        }

        return $this->render('common/modal/send-by-mail-content.html.twig',[
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/suggest-by-mail", name="suggest_by_mail")
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
                $this->mailSenderService->getSenderForSuggestion(),
                $object->getEmail()
            )) {
                return $this->render('common/modal/send-by-mail-success.html.twig');
            } else {
                $form->addError(
                    new FormError("Une erreur est survenue lors de l'envoie de l'e-mail \n veuillez reessayer plus tard SVP.")
                );
            }
        }

        return $this->render('common/modal/suggestion-content.html.twig', [
                'form' => $form->createView(),
            ]);
    }
}
