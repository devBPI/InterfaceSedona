<?php

namespace App\Service;

use Monolog\Logger;
use Twig\Environment;

class MailSenderService
{
	/**
	 * A déporter dans le fichier ENV
	 */
	const PURCHASE_SUGGESTION_EMAIL = 'organigramme.0302@bpi.fr';

	/**
 	 * @var Logger
	 */
	private $logger;

	/**
	 * @var Environment
	 */
	private $twig;

	/**
	 * @var \Swift_Mailer
	 */
	private $mailer;
	/**
	 * @var string
	 */
	private $sender;
	/**
	 * @var string
	 */
	private $replyTo;

	/**
     	 * MailSenderService constructor.
	 *
	 * @param string $sender
	 * @param string $replyTo
	 * @param Environment $twig
	 * @param \Swift_Mailer $mailer
	 */
	public function __construct(Logger $logger, string $sender, string $replyTo, Environment $twig, \Swift_Mailer $mailer)//, Logger $logger)
	{
		$this->logger = $logger;
		$this->mailer = $mailer;
		$this->twig = $twig;
		$this->sender = $sender;
		$this->replyTo = $replyTo;
	}

	/**
	 * @param string $templateName
	 * @param array $context
	 * @param string|null $reciever
	 * @param string|null $senderEmail
	 * @param \Swift_Attachment|null $attachment
	 * @return int
	 * @throws \Throwable
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\SyntaxError
	 */
	public function sendEmail(
		string $templateName,
		array $context,
		string $from=null,
		$to=null,
		string $replyTo=null,
		\Swift_Attachment $attachment = null
	)
	{
		/*$this->logger->error("#####################");
		$this->logger->error("Default replyTo : ".$this->replyTo);
		$this->logger->error("From : ".$from);
		$this->logger->error("To : ".$to);
		$this->logger->error("#####################");/**/
		$context = $this->twig->mergeGlobals($context);
		$template = $this->twig->resolveTemplate($templateName);
		$subject = $template->renderBlock('subject', $context);
		$textBody = $template->renderBlock('body_text', $context);
		$htmlBody = $template->renderBlock('body_html', $context);

		$from  = empty($from) ? $this->replyTo : $from;
		$to  = empty($to) ? $this->replyTo : $to;
		$replyTo  = empty($replyTo) ? $this->replyTo : $replyTo;

		/*$this->logger->info("#####################");
		$this->logger->info("Subject : " . $subject);
		$this->logger->info("From    : " . $from);
		$this->logger->info("To      : " . $to);
		$this->logger->info("ReplyTo : " . $replyTo);
		$this->logger->info("Message : " . $htmlBody);
		$this->logger->info("#####################");/**/

		$message = (new \Swift_Message($subject))
			->setSubject($subject)
			->setFrom($from)
			->setTo($to)
			->setReplyTo($replyTo);//$replyTo);

		if (!empty($htmlBody))
		{
			$message
			->setBody($htmlBody, 'text/html')
			->addPart($textBody, 'text/plain');
		}
		else
		{
			$message->setBody($textBody);
		}
		if ($attachment !== null)
		{
			$message->attach($attachment);
		}

		return $this->mailer->send($message);
	}

	/*
	 * @param string $templateName
	 * @param array $context
	 * @param string|null $reciever
	 * @param string|null $senderEmail
	 * @param \Swift_Attachment|null $attachment
	 * @return int
	 * @throws \Throwable
	 * @throws \Twig\Error\LoaderError
	 * @throws \Twig\Error\SyntaxError
	 */
	/*public function sendMail(
	string $templateName,
	array $context,
	$reciever=null,
	\Swift_Attachment $attachment = null,
	string $replyTo = null
	)
	{
		$context = $this->twig->mergeGlobals($context);
		$template = $this->twig->resolveTemplate($templateName);
		$subject = $template->renderBlock('subject', $context);
		$textBody = $template->renderBlock('body_text', $context);
		$htmlBody = $template->renderBlock('body_html', $context);

		$reciever  = empty($reciever) ? $this->replyTo : $reciever;
		$replyTo  = empty($replyTo) ? $this->replyTo : $replyTo;

		$message = (new \Swift_Message($subject))
			->setSubject($subject)
			->setFrom($replyTo)
			->setReplyTo($replyTo)
			->setTo($reciever);

		if (!empty($htmlBody))
		{
			$message
			->setBody($htmlBody, 'text/html')
			->addPart($textBody, 'text/plain');
		}
		else
		{
			$message->setBody($textBody);
		}
		if ($attachment !== null)
		{
			$message->attach($attachment);
		}

		return $this->mailer->send($message);
	}*/

	/**
	 * @return array
	 */
	public function getSenderForSuggestion()
	{
		return [self::PURCHASE_SUGGESTION_EMAIL, $this->replyTo];
	}
}
