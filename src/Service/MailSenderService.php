<?php


namespace App\Service;


use App\Model\Form\ReportError;
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
     * @var string
     */
    private $sender;

    /**
     * MailSenderService constructor.
     * @param string $sender
     * @param Environment $twig
     * @param \Swift_Mailer $mailer
     */
    public function __construct(string $sender, Environment $twig, \Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->sender = $sender;
    }

    /**
     * @param $templateName
     * @param $context
     * @param $fromEmail
     * @param $toEmail
     * @param null $copyTo
     * @return int
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendMail($templateName, $context, $fromEmail, $toEmail, $replyTo=null, \Swift_Attachment $attachment=null)
    {
        $context    = $this->twig->mergeGlobals($context);
        $template   = $this->twig->resolveTemplate($templateName);

        $subject    = $template->renderBlock('subject', $context);
        $textBody   = $template->renderBlock('body_text', $context);
        $htmlBody   = $template->renderBlock('body_html', $context);
        $sender = 'no-reply@bpi-calogue.fr';
        $message = (new \Swift_Message($subject))
            ->setSubject($subject)
            ->setFrom($sender)
            ->setTo($toEmail)
        ;

        if ($replyTo!==null){
            $message->setReplyTo($replyTo);
        }

        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }
        if ($attachment!==null){
            $message->attach($attachment);
        }

        return $this->mailer->send($message);
    }
}
