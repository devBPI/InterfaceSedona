<?php
declare(strict_types=1);

namespace App\Twig;


use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Yaml\Yaml;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class MenuCreator
 * @package App\Twig
 */
class MenuCreator extends AbstractExtension
{
    /** @var array  */
    private $menus;

    /**
     * MenuCreator constructor.
     * @param RequestStack $requestStack
     * @param string $translationPath
     */
    public function __construct(RequestStack $requestStack, string $translationPath)
    {
        $locale = $requestStack->getCurrentRequest()->getLocale();
        $this->menus = Yaml::parseFile($translationPath.DIRECTORY_SEPARATOR.'menu.'.$locale.'.yml');
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('header_menu', [$this, 'getHeaderMenu']),
            new TwigFunction('footer_menu', [$this, 'getFooterMenu'])
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'menu_creator';
    }

    /**
     * @return array
     */
    public function getHeaderMenu(): array
    {
        return $this->menus['header'];
    }
    /**
     * @return array
     */
    public function getFooterMenu(): array
    {
        return $this->menus['footer'];
    }
}

