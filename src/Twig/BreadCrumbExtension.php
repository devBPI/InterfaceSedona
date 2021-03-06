<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 30/10/19
 * Time: 17:10
 */

namespace App\Twig;

use App\Service\BreadCrumbBuilder;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BreadCrumbExtension extends AbstractExtension
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var BreadCrumbBuilder
     */
    private $builder;

    /**
     * BreadCrumbExtension constructor.
     * @param RequestStack $requestStack
     * @param BreadCrumbBuilder $builder
     */
    public function __construct(RequestStack $requestStack, BreadCrumbBuilder $builder)
    {
        $this->requestStack = $requestStack;

        $this->builder = $builder;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('breadcrumb', [$this, 'breadcrumb'])
        ];
    }

    public function breadcrumb()
    {
        $this->builder->build($this->requestStack->getCurrentRequest());

        return $this->builder->getBctService();
    }
}

