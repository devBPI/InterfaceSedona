<?php
declare(strict_types=1);

namespace App\Model\Form;

use Symfony\Component\Validator\Constraints as Asset;

/**
 * Class ShareByMail
 * @package App\Model\Form
 */
final class ShareByMail implements MessageInfoInterface
{
    use MessageInfoTrait;
    /**
     * @var string
     * @Asset\NotBlank(message="object.empty");
     */
    private $object;
    /**
     * @var string
     * @Asset\NotBlank(message="message.empty");
     */
    private $message;

    /**
     * @var string
     * @Asset\NotBlank(message="sender.empty");
     * @Asset\Email(message="email.format");
     */
    private $sender;

    /**
     * @var string
     * @Asset\NotBlank(message="reciever.empty");
     * @Asset\Email(message="email.format");
     */
    private $reciever;

    /**
     * @var string
     */
    private $link;

    /**
     * @return null|string
     */
    public function getSender(): ?string
    {
        return $this->sender;
    }

    /**
     * @param string $sender
     * @return ShareByMail
     */
    public function setSender(string $sender): ShareByMail
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @param string $reciever
     * @return ShareByMail
     */
    public function setReciever(string $reciever): ShareByMail
    {
        $this->reciever = $reciever;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getReciever(): ?string
    {
        return $this->reciever;
    }

    /**
     * @return null|string
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string|null $link
     * @return ShareByMail
     */
    public function setLink(string $link=null):ShareByMail
    {
        $this->link = $link;

        return $this;
    }
}
