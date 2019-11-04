<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 12/09/19
 * Time: 16:50
 */

namespace App\Utils;

use App\Model\Interfaces\NoticeInterface;
use App\Model\Traits\BreadcrumbTrait;
use App\Model\Traits\NoticeTrait;

class NavigationNotice implements NoticeInterface
{
    const bread_crumb = 'notice';
    use NoticeTrait, BreadcrumbTrait;

    /**
     * NavigationNotice constructor.
     * @param string $permalink
     * @param string $type
     */
    public function __construct(string $permalink, string $type)
    {
        $this->type = $type;
        $this->permalink = $permalink;
    }

    /**
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->permalink;
    }
}

