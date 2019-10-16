<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 02/10/19
 * Time: 11:01
 */

namespace App\Controller;

use App\Service\NoticeBuildFileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ExportNoticeType;
use App\Model\Form\ExportNotice;
use App\Service\MailSenderService;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ExportController extends AbstractController
{
    /**
     * @var NoticeBuildFileService
     */
    private $buildFileContent;

    /**
     * ExportController constructor.
     * @param NoticeBuildFileService $buildFileContent
     */
    public function __construct(NoticeBuildFileService $buildFileContent)
    {
        $this->buildFileContent = $buildFileContent;
    }

    /**
     * @Route("/share-by-mail/{type}", name="share_notice_search_by_mail")
     * @param Request $request
     * @param string $type
     * @param MailSenderService $mailSenderService
     * @return Response
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendNoticesOnAttachementAction(Request $request, string $type, MailSenderService $mailSenderService): Response
    {
        $form = $this->createForm(ExportNoticeType::class, new ExportNotice());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $object = $form->getData();
            $object->setObject('partage par mail de la recherche des notices ');
            $filename = 'search-'.date('Y-m-d_h-i-s').'.'.$object->getFormatType();
            $content = $this->buildFileContent->buildContent($object, $type, $object->getFormatType());
            $attachment = new \Swift_Attachment($content, $filename, sprintf('application/%s', $object->getFormatType()));

            if ($mailSenderService->sendMail(
                'common/modal/content.email.twig',
                ['data' => $object],
                'no-reply@sedona.fr',
               'no-reply@sedona.fr' ,
                'no-reply@sedona.fr',
                $attachment
            )) {

                return $this->render('common/modal/share-success.html.twig');
            } else {
                $form->addError(
                    new FormError("Une erreur est survenue lors de l'envoie de l'e-mail \n veuillez reessayer plus tard SVP.")
                );
            }
        }

        return $this->render(
            'common/modal/share-notices.html.twig',
            [
                'form' => $form->createView(),
                'type' => $type,
                'action_route' => $this->generateUrl('share_notice_search_by_mail', ['type'=>$type]),
            ]
        );
    }

    /**
     * @Route("/share-by-mail/{type}/{permalink}", name="share_notice_by_mail", requirements={"permalink"=".+"})
     * @param Request $request
     * @param string $type
     * @param MailSenderService $mailSenderService
     * @return Response
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendNoticeWithAttachementAction(Request $request,string $permalink, string $type, MailSenderService $mailSenderService): Response
    {
        $form = $this->createForm(ExportNoticeType::class, new ExportNotice());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            /** @var ExportNotice $object */
            $object = $form->getData();
            $filename = 'notice-'.date('Y-m-d_h-i-s').'.'.$object->getFormatType();
            $object
                ->setNotices($permalink)
                ->setAuthorities($permalink);
            $content = $this->buildFileContent->buildContent($object, $type, $object->getFormatType());
            $attachment = new \Swift_Attachment($content, $filename, sprintf('application/%s', $object->getFormatType()));
            $object->setObject(sprintf('partage par mail de la notice %s', $permalink));

            if ($mailSenderService->sendMail(
                'common/modal/content.email.twig',
                ['data' => $object],
                'no-reply@sedona.fr',
               $object->getReceiver() ,
                'no-reply@sedona.fr',
                $attachment
            )) {

                return $this->render('common/modal/share-success.html.twig');
            } else {
                $form->addError(
                    new FormError("Une erreur est survenue lors de l'envoie de l'e-mail \n veuillez reessayer plus tard SVP.")
                );
            }
        }

        return $this->render(
            'common/modal/share-notices.html.twig',
            [
                'form' => $form->createView(),
                'type' => $type,
                'action_route' => $this->generateUrl('share_notice_by_mail', ['type'=>$type, 'permalink'=>$permalink]),
            ]
        );
    }
}
