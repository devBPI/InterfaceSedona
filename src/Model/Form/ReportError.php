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
     */
    private $object;
    /**
     * @var string
     * @Asset\NotBlank(message="message.empty");
     */
    private $message;

    /**
     * @var string
     */
    private $permalink;

    public function __construct(?string $permalink)
    {
        $this->permalink = $permalink;
    }

    public function getPermalink(): ?string
    {
        return $this->permalink;
    }

    public function setPermalink(?string $permalink): self
    {
        $this->permalink = $permalink;
        return $this;
    }


}
