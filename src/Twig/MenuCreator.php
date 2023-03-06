<?php
declare(strict_types=1);

namespace App\Twig;


use Symfony\Component\HttpFoundation\Request;
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
		if ($requestStack->getCurrentRequest() instanceof Request)
		{
			$locale = $requestStack->getCurrentRequest()->getLocale();
			$this->menus = Yaml::parseFile($translationPath.DIRECTORY_SEPARATOR.'menu.'.$locale.'.yml');
		}
	}

	/**
	 * @return array|TwigFunction[]
	 */
	public function getFunctions(): array
	{
		return [
			new TwigFunction('header_menu', [$this, 'getHeaderMenu']),
			new TwigFunction('help_menu',   [$this, 'getHelpMenu']),
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
		if (is_array($this->menus))
			return $this->menus['header'];
		return [];
	}

	/**
	 * @return array
	 */
	public function getHelpMenu(): array
	{
		if (is_array($this->menus))
			return $this->menus['help'];
		return [];
	}

	/**
	 * @return array
	 */
	public function getFooterMenu(): array
	{
		if (is_array($this->menus))
			return $this->menus['footer'];
		return [];
	}
}

