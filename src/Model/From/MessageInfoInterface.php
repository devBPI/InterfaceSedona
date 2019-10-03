<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 30/09/19
 * Time: 16:51
 */

namespace App\Model\From;


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

