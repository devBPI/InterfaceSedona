<?php
declare(strict_types=1);

namespace App\Model\Search;

use App\Model\Interfaces\NavigationInterface;

/**
 * Class AuthorityList
 * @package App\Model\Search
 */
final class AuthorityList implements NavigationInterface
{
    private const LIMIT = 5;

    private $list = [];

    /**
     * AuthorityList constructor.
     * @param array $list
     */
    public function __construct(array $list)
    {
        $this->list = $list;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return self::LIMIT;
    }

    /**
     * @return array
     */
    public function getNoticesList(): array
    {
        return $this->list;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return 1;
    }

    /**
     * @return int
     */
    public function getRows(): int
    {
        return self::LIMIT;
    }
}

