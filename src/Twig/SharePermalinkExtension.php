<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class SharePermalinkExtension
 * @package App\Twig
 */
class SharePermalinkExtension extends AbstractExtension
{
    /**
     * @var RequestStack
     */
    private $requestStack;


    /**
     * BreadCrumbExtension constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('current_permalink', [$this, 'getCurrentPermalink'])
        ];
    }

    /**
     * @return string|null
     */
    public function getCurrentPermalink(): ?string
    {
        $request = $this->requestStack->getCurrentRequest();
        if (array_key_exists('permalink', $request->get('_route_params'))) {
            return strtok($request->getUri(), '?');
        }

        return null;
    }
}

