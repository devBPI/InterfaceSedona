<?php
declare(strict_types=1);

namespace App\Model\Form;
use Symfony\Component\Validator\Constraints as Asset;

/**
 * Class ReportError
 * @package App\Model\Form
 */
final class ReportError implements MessageInfoInterface, PersonneInterface
{
    use MessageInfoTrait, PersonneTrait;
    /**
     * @var string
     * @Asset\NotBlank(message="object.empty");
     */
    private $object;
    /**
     * @var string
     * @Asset\NotBlank(message="message.empty");
     */
    private $message;

}
