<?php

namespace App\Model\Interfaces;


interface NavigationInterface
{
    /**
     * @return int
     */
    public function getTotalCount(): int;

    /**
     * @return array|NoticeInterface[]
     */
    public function getNoticesList(): array;

    /**
     * @return int
     */
    public function getPage(): int;

    /**
     * @return int
     */
    public function getRows(): int;

}
