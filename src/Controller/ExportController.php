<?php

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
use Symfony\Component\Translation\TranslatorInterface;


final class ExportController extends AbstractController
{
	/**
	 * @var NoticeBuildFileService
	 */
	private $buildFileContent;
	/**
	 * @var MailSenderService
	 */
	private $mailSenderService;
	/**
	 * @var TranslatorInterface
	 */
	private $translator;

	/**
	 * ExportController constructor.
	 * @param NoticeBuildFileService $buildFileContent
	 * @param MailSenderService $mailSenderService
	 * @param TranslatorInterface $translator
	 */
	public function __construct(NoticeBuildFileService $buildFileContent, MailSenderService $mailSenderService, TranslatorInterface $translator)
	{
		$this->buildFileContent = $buildFileContent;
		$this->mailSenderService = $mailSenderService;
		$this->translator = $translator;
	}

	/**
	 * @Route("/share-by-mail/{type}", name="share_notice_search_by_mail")
	 * @param Request $request
	 * @param string $type
	 * @return Response
	 * @throws \Throwable
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\RuntimeError
	 * @throws \Twig\Error\SyntaxError
	 */
	public function sendNoticesOnAttachementAction(Request $request, string $type): Response
	{
		$form = $this->createForm(ExportNoticeType::class, new ExportNotice());
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			/** @var ExportNotice $object */
			$object = $form->getData();
			$object->setObject($this->translator->trans('modal.export.subject'));
			$filename = 'Vos-references-'.date('Y-m-d').'.'.$object->getFormatType();
			$content = $this->buildFileContent->buildContent($object, $type, $object->getFormatType());
			$attachment = new \Swift_Attachment($content, $filename, sprintf('application/%s', $object->getFormatType()));

			if($this->mailSenderService->sendEmail(
				'common/modal/content-export-notice.email.twig',
				['data' => $object],
				null,
				$object->getReciever(),
				null,
				$attachment
			))
			{
				return $this->render('common/modal/share-success.html.twig');
			}
			else
			{
				$form->addError(new FormError("Une erreur est survenue lors de l'envoie de l'e-mail \n veuillez reessayer plus tard SVP."));
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
	 * @return Response
	 * @throws \Throwable
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\SyntaxError
	 */
	public function sendNoticeWithAttachementAction(Request $request,string $permalink, string $type): Response
	{
		$form = $this->createForm(ExportNoticeType::class, new ExportNotice());
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid())
		{
			/** @var ExportNotice $object */
			$object = $form->getData();
			$filename = 'Vos-references-'.date('Y-m-d_h-i-s').'.'.$object->getFormatType();
			$object->setNotices($permalink)
			->setAuthorities($permalink)
			->setIndices($permalink);
			$content = $this->buildFileContent->buildContent($object, $type, $object->getFormatType());
			$attachment = new \Swift_Attachment($content, $filename, sprintf('application/%s', $object->getFormatType()));
			$object->setObject($this->translator->trans('modal.export.subject'));

			if ($this->mailSenderService->sendEmail(
				'common/modal/content-export-notice.email.twig',
				['data' => $object],
				null,
				$object->getReciever(),
				null,
				$attachment
			))
			{
				return $this->render('common/modal/share-success.html.twig');
			}
			else
			{
				$form->addError(new FormError("Une erreur est survenue lors de l'envoie de l'e-mail \n veuillez reessayer plus tard SVP."));
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

	/**
	 * @param Request $request
	 */
	public function checkNoticeAction(Request $request)
	{
		die('toto'); die;
//        return $this->render('user/modal/comments-edit.html.twig',);
	}
}
