<?php
declare(strict_types=1);

namespace App\Model\Form;

use Symfony\Component\Validator\Constraints as Asset;

/**
 * Class ShareByMail
 * @package App\Model\Form
 */
class ShareByMail implements MessageInfoInterface
{
    use MessageInfoTrait;

    /**
     * @var string
     * @Asset\NotBlank();
     * @Asset\Email();
     */
    private $sender;

    /**
     * @var string
     * @Asset\NotBlank();
     * @Asset\Email();
     */
    private $receiver;

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
}
