<?php

namespace App\Model\Form;


/**
 * Trait MessageInfoTrait
 * @package App\Model\Form
 */
trait MessageInfoTrait
{

    /**
     * @return string
     */
    public function getObject(): ?string
    {
        return $this->object;
    }

    /**
     * @param string $object
     * @return $this
     */
    public function setObject(string $object)
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
     * @return $this
     */
    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

}

