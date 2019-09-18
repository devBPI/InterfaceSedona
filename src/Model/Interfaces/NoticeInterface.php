<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 10/09/19
 * Time: 15:59
 */

namespace App\Model\Interfaces;


interface NoticeInterface
{
    /**
     * @return string
     */
    public function getPermalink():string;

    /**
     * @return string
     */
    public function getType():string;
    /**
     *
     * @return string
     */
    public function getTitle():string;

}
