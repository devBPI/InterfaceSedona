<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 29/10/19
 * Time: 15:12
 */

namespace App\Service;


final class BreadCrumbStack
{
    /**
     * @var string
     */
    private $label;
    /**
     * @var
     */
    private $link;

    /**
     * BreadCrumbStack constructor.
     * @param string $label
     * @param $link
     */
    public function __construct(string $label, string $link = null)
    {
        $this->label = $label;
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

}
