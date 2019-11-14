<?php


namespace App\Service;


use Twig\Environment;

class MailSenderService
{
    const RECIEVER_EMAIL = 'catalogue.public@bpi.fr';
    const PURCHASE_SUGGESTION_EMAIL    = 'organigramme.0302@bpi.fr';
    const SENDER_EMAIL  =  'catalogue.public@bpi.fr';
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
    public function __construct(string $sender, string $replyTo, Environment $twig, \Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->sender = $sender;
        $this->replyTo = $replyTo;
    }

    /**
     * @param $templateName
     * @param $context
     * @param $toEmail
     * @param \Swift_Attachment|null $attachment
     * @return int
     * @throws \Throwable
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendMail(
        $templateName,
        $context,
        $reciever=null,
        $senderEmail=null,
        \Swift_Attachment $attachment = null
    ) {

        $context = $this->twig->mergeGlobals($context);
        $template = $this->twig->resolveTemplate($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        if (!$senderEmail){
            $senderEmail = $this->sender;
        }
        if (!$reciever){
            $reciever =  $this->replyTo;
        }
        $message = (new \Swift_Message($subject))
            ->setSubject($subject)
            ->setFrom($senderEmail)
            ->setTo($reciever );

            $message->setReplyTo($this->replyTo);


        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }
        if ($attachment !== null) {
            $message->attach($attachment);
        }

        return $this->mailer->send($message);
    }
}
