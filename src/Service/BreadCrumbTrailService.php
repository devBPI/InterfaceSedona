<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 29/10/19
 * Time: 15:11
 */

namespace App\Service;

use Countable;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class BreadCrumbTrailService
 * @package App\Service
 */
class BreadCrumbTrailService implements \Iterator,  Countable
{
    /**
     * @var BreadCrumbStack[]
     */
    private $stack;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var UrlGeneratorInterface
     */
    private $routeCollection;
    /**
     * @var int
     */
    private $index=0;

    /**
     * BreadCrumbTrailService constructor.
     * @param TranslatorInterface $translator
     * @param UrlGeneratorInterface $routeCollection
     */
    public function __construct(TranslatorInterface $translator, UrlGeneratorInterface $routeCollection)
    {
        $this->translator = $translator;
        $this->routeCollection = $routeCollection;
        $this->initialize();
    }

    private  function initialize()
    {
        $this->index = 0;
        $this->stack[$this->index] = new BreadCrumbStack(
            $this->translator->trans('page.home.page_title'),
            $this->routeCollection->generate('home')
        );
    }

    public function reset()
    {
        $this->initialize();

        return $this;
    }

    /**
     * @param $link
     * @param $label
     * @param array $arrayLink
     * @param array $parameterLink
     * @return BreadCrumbStack
     */
    public function add(string $routeName = null ,string $label, array $routeParam=[], array $labelParam = []):BreadCrumbStack
    {
        if (!empty($routeName)) {
            $this->stack[] = new BreadCrumbStack(
                $this->translator->trans($label, $labelParam),
                $this->routeCollection->generate($routeName, $routeParam)
            );
        } else {
            $this->stack[] = new BreadCrumbStack($this->translator->trans($label, $labelParam));
        }

        return  $this->stack[$this->index];
    }

    /**
     * @return BreadCrumbStack|mixed
     */
    public function current()
    {
        return $this->stack[$this->index];
    }

    public function next()
    {
        $this->index++;
    }

    /**
     * @return int|mixed
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * @return bool
     */
    public function valid():bool
    {
        return isset($this->stack[$this->index]);
    }

    /**
     * @return null|void
     */
    public function rewind()
    {
        if ($this->index-1<=-1){
            return;
        }

        if (! isset($this->stack[$this->index]) && ! array_key_exists($this->index, $this->stack)) {
            return null;
        }

        unset($this->stack[$this->index]);

        --$this->index;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->stack);
    }

}

