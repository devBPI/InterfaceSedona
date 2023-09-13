<?php
declare(strict_types=1);

namespace App\Model\Form;

use Symfony\Component\Validator\Constraints as Asset;

/**
 * Class SendByMail
 * @package App\Model\Form
 */
class SendByMail extends ExportNotice  implements ExportInterface
{

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     * @Asset\Email(message="email.format");
     */
    private $sender;

    /**
     * @var string
     * @Asset\NotBlank(message="reciever.empty");
     * @Asset\Email(message="email.format");
     */
    private $reciever;

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getSender(): ?string
    {
        return $this->sender;
    }

    public function setSender(string $sender): self
    {
        $this->sender = $sender;
        return $this;
    }

    public function setReciever(string $reciever): self
    {
        $this->reciever = $reciever;
        return $this;
    }

    public function getReciever(): ?string
    {
        return $this->reciever;
    }

    public function getMaxExportNotice() :int
    {
        return 50;
    }
}
