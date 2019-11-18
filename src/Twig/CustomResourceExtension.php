<?php

namespace App\Twig;

use Symfony\Component\Filesystem\Filesystem;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;

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
     * @var string
     */
    protected $root_dir;

    /**
     * CustomResourceExtension constructor.
     * @param Filesystem $fs
     * @param string $root_dir
     */
    public function __construct(Filesystem $fs, string $root_dir)
    {

        $this->fs = $fs;
        $this->root_dir = $root_dir."/../";
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('get_package_info',[$this, 'getPackageInfo'])
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

}
