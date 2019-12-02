<?php

namespace App\Twig;

use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Class CustomResourceExtension
 * @package AppBundle\Twig
 */
class CustomResourceExtension extends AbstractExtension
{
    /**
     * @var Filesystem
     */
    protected $fs;
    /**
     * @var Environment
     */
    private $template;
    /**
     * @var string
     */
    protected $root_dir;

    /**
     * CustomResourceExtension constructor.
     * @param Filesystem $fs
     * @param Environment $template
     * @param string $root_dir
     */
    public function __construct(Filesystem $fs, Environment $template, string $root_dir)
    {
        $this->fs = $fs;
        $this->template = $template;
        $this->root_dir = $root_dir."/../";
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('get_package_info',[$this, 'getPackageInfo']),
            new TwigFunction('template_exist',[$this, 'checkExistTemplate'])
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('file_exist', [$this, 'fileExistFilter']),
            new TwigFilter('file_get_content', [$this, 'fileGetContentFilter'])
        ];
    }

    /**
     * @param string $path
     * @return bool
     */
    public function fileExistFilter(string $path)
    {
        return $this->fs->exists($this->root_dir.$path);
    }

    /**
     * @param string $path
     * @return string
     */
    public function fileGetContentFilter(string $path)
    {
        return $this->fileExistFilter($path) ? file_get_contents($this->root_dir.$path) : "";
    }

    /**
     * @return mixed
     */
    public function getPackageInfo()
    {
        $content = $this->fileExistFilter('package_info.json') ? file_get_contents($this->root_dir.'package_info.json') : "{}";
        return json_decode($content, true);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function checkExistTemplate(string $path): bool
    {
        return $this->template->getLoader()->exists($path);
    }

}
