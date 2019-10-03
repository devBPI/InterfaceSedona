<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 30/09/19
 * Time: 15:34
 */

namespace App\Model\From;

use Symfony\Component\Validator\Constraints as Asset;

/**
 * Class ShareByMail
 * @package App\Model\From
 */
class ShareByMail implements MessageInfoInterface
{
    use MessageInfoTrait;

    /**
     * @var string
     * @Asset\NotBlank();
     */
    private $sender;

    /**
     * @var string
     * @Asset\NotBlank();
     */
    private $reciever;

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
}
