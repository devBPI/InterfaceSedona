<?php
declare(strict_types=1);

namespace App\Twig;


use App\Service\SelectionListService;
use Doctrine\ORM\NonUniqueResultException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class UserExtension
 * @package App\Twig
 */
class UserExtension extends AbstractExtension
{
    /**
     * @var SelectionListService
     */
    private $selectionListService;

    /**
     * UserExtension constructor.
     *
     * @param SelectionListService $selectionListService
     */
    public function __construct(SelectionListService $selectionListService)
    {
        $this->selectionListService = $selectionListService;
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('count_selection_documents', [$this, 'getCountSelectionDocuments']),
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'user_extension';
    }

    /**
     * @return int
     */
    public function getCountSelectionDocuments(): int
    {
        try {
            return $this->selectionListService->getCountDocuments();
        } catch (NonUniqueResultException $e) {
            return 0;
        }
    }

}

