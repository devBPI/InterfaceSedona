<?php
declare(strict_types=1);

namespace App\Twig;


use App\Service\ImageBuilderService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class StringExtension
 * @package App\Twig
 */
class BinaryImageExtension extends AbstractExtension
{
    /**
     * @var ImageBuilderService
     */
    private $imageService;

    /**
     * BinaryImageExtension constructor.
     * @param ImageBuilderService $imageService
     */
    public function __construct(ImageBuilderService $imageService)
    {
        $this->imageService = $imageService;
    }


    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('image_to_base64', [$this, 'image64']),
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'search_filters_extension';
    }


    /**
     * @param $path
     * @return null|string
     */
    public function image64($path)
    {
        return $this->imageService->getimage64(substr($path, 1));
    }
}
