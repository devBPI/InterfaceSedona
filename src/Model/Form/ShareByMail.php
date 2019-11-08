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
     * @Asset\NotBlank(message="email.empty");
     * @Asset\Email();
     */
    private $sender;

    /**
     * @var string
     * @Asset\NotBlank(message="email.empty");
     * @Asset\Email();
     */
    private $receiver;

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
     * @param string $receiver
     * @return ShareByMail
     */
    public function setReceiver(string $receiver): ShareByMail
    {
        $this->receiver = $receiver;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getReceiver(): ?string
    {
        return $this->receiver;
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
