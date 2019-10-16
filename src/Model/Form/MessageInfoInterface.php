<?php

namespace App\Model\Form;

/**
 * Interface MessageInfoInterface
 * @package App\Model\Form
 */
interface MessageInfoInterface
{
    /**
     * @return null|string
     */
    public function getObject(): ?string;

    /**
     * @return null|string
     */
    public function getMessage(): ?string;

}

