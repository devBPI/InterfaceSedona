<?php

namespace App\Model\Form;

use Symfony\Component\Validator\Constraints as Asset;

/**
 * Trait MessageInfoTrait
 * @package App\Model\Form
 */
trait MessageInfoTrait
{
    /**
     * @var string
     * @Asset\NotBlank(message="text.empty");
     */
    private $object;

    /**
     * @var string
     * @Asset\NotBlank(message="text.empty");
     */
    private $message;


    /**
     * @return string
     */
    public function getObject(): ?string
    {
        return $this->object;
    }

    /**
     * @param string $object
     * @return MessageInfoInterface|MessageInfoTrait
     */
    public function setObject(string $object): MessageInfoInterface
    {
        $this->object = $object;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return MessageInfoInterface|MessageInfoTrait
     */
    public function setMessage(string $message): MessageInfoInterface
    {
        $this->message = $message;

        return $this;
    }

}

