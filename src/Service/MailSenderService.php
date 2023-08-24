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

	public function __construct(string $sender, string $replyTo, Environment $twig, \Swift_Mailer $mailer)
	{
		$this->mailer = $mailer;
		$this->twig = $twig;
		$this->sender = $sender;
		$this->replyTo = $replyTo;
	}

    /**
     * @param string $templateName
     * @param array<mixed> $context
     * @param array<string>|null $to
     * @param string|null $replyTo
     * @return int
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
	public function sendEmail(
		string  $templateName,
		array   $context,
        array   $to = null,
		string  $replyTo = null
	) :int
	{
		$context = $this->twig->mergeGlobals($context);
		$template = $this->twig->resolveTemplate($templateName);
		$subject = $template->renderBlock('subject', $context);
		$textBody = $template->renderBlock('body_text', $context);
		$htmlBody = $template->renderBlock('body_html', $context);

		$to  = empty($to) ? $this->replyTo : $to;
		$replyTo  = empty($replyTo) ? $this->replyTo : $replyTo;

		$message = (new \Swift_Message($subject))
			->setSubject($subject)
			->setFrom($this->sender)
			->setTo($to)
			->setReplyTo($replyTo);

		if (!empty($htmlBody)) {
			$message
			->setBody($htmlBody, 'text/html')
			->addPart($textBody, 'text/plain');
		} else {
			$message->setBody($textBody);
		}

		return $this->mailer->send($message);
	}

    /**
     * @return array<string>
     */
	public function getSenderForSuggestion() :array
	{
		return [self::PURCHASE_SUGGESTION_EMAIL, $this->replyTo];
	}
}
