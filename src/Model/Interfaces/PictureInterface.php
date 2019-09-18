<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 30/08/19
 * Time: 14:28
 */
declare(strict_types=1);

namespace App\Model\Interfaces;


interface PictureInterface
{
    /**
     * @return null|string
     */
    public function getDescription():?string;

    /**
     * @return null|string
     */
    public function getUrl():?string;
}