<?php
declare(strict_types=1);

namespace App\Model\From;
use Symfony\Component\Validator\Constraints as Asset;

/**
 * Class ReportError
 * @package App\Model\From
 */
class ReportError implements MessageInfoInterface, PersonneInterface
{
    use MessageInfoTrait, PersonneTrait;
}
