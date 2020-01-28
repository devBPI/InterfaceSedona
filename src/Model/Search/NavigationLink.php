<?php
declare(strict_types=1);

namespace App\Model\Search;

use App\Model\Interfaces\NoticeInterface;
use App\Model\Traits\NoticeTrait;

/**
 * Class NavigationLink
 * @package App\Model\Searc
 */
class NavigationLink implements NoticeInterface
{
    use NoticeTrait;

    /**
     * NavigationLink constructor.
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

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return 'navigationLink';
    }
}

