<?php

namespace App\Model\Interfaces;


interface NoticeInterface
{
    /**
     * @return string
     */
    public function getPermalink(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return string
     */
    public function getClassName(): string;

}
