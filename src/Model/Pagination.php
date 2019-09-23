<?php
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 16/08/19
 * Time: 10:53
 */

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Pagination
 * @package App\Model
 */
class Pagination
{
    /**
     * @var integer
     * @JMS\Type("integer")
     */
    private $rows;
    /**
     * @var integer
     * @JMS\Type("integer")
     */
    private $page;

    /**
     * @return int
     */
    public function getRows(): int
    {
        return $this->rows;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }
}
