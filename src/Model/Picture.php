<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 30/08/19
 * Time: 14:26
 */
declare(strict_types=1);

namespace App\Model;
use App\Model\Interfaces\PictureInterface;
use JMS\Serializer\Annotation as JMS;


class Picture implements PictureInterface
{

    /**
     * @var string
     * @JMS\TYPE("string")
     *
     */
    private $description;

    /**
     * @var string
     * @JMS\TYPE("string")
     *
     */
    private $url;

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

}