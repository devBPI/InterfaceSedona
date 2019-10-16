<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: infra
 * Date: 11/10/19
 * Time: 14:31
 */

namespace App\Exception;


use Symfony\Component\HttpFoundation\File\Exception\FileException;

class DirectoryNotFoundException extends FileException
{
    public function __construct($path = null)
    {
        if (null === $path) {
            $message = 'Directory could not be found.';
        } else {
            $message = sprintf('Directory "%s" could not be found.', $path);
        }

        parent::__construct($message);
    }
}
