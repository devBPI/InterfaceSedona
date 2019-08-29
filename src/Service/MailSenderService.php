<?php


namespace App\Service;


use App\Model\From\ReportError;
use Twig\Environment;

class MailSenderService
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * MailSenderService constructor.
     * @param Environment $twig
     * @param \Swift_Mailer $mailer
     */
    public function __construct(Environment $twig, \Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param $templateName
     * @param $context
     * @param $fromEmail
     * @param $toEmail
     * @return int
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendMail($templateName, $context, $fromEmail, $toEmail)
    {
        $context = $this->twig->mergeGlobals($context);
        $template = $this->twig->createTemplate($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $message = (new \Swift_Message($subject))
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail);

        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }

        return $this->mailer->send($message);
    }
}
