<?php
declare(strict_types=1);

namespace App\Model\Form;

/**
 * Class ReportError
 * @package App\Model\Form
 */
class ReportError implements MessageInfoInterface, PersonneInterface
{
    use MessageInfoTrait, PersonneTrait;
}