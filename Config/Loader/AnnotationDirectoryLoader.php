<?php

/*
 * This file is part of the RPC Bundle for Symfony2.
 *
 * (c) Pavel Gopanenko <pavelgopanenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itart\Bundle\RpcBundle\Config\Loader;

use Itart\Bundle\RpcBundle\Service\ServiceCollection;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * Description of the annotation service.
 *
 * @package    ItartRpcBundle
 * @subpackage Config
 * @author     Pavel Gopanenko <pavelgopanenko@gmail.com>
 * @copyright  2012 Pavel Gopanenko
 */
class AnnotationDirectoryLoader extends FileLoader
{
    private $_annotationLoader;

    /**
     * Constructor.
     *
     * @param FileLocator          $locator          A FileLocator instance
     * @param AnnotationFileLoader $annotationLoader An AnnotationFileLoader instance
     */
    public function __construct(FileLocator $locator, AnnotationFileLoader $annotationLoader)
    {
        parent::__construct($locator);

        $this->_annotationLoader = $annotationLoader;
    }

    /**
     * Loads from annotations from a directory.
     *
     * @param string $path A directory path
     * @param string $type The resource type
     *
     * @return ServiceCollection A ServiceCollection instance
     */
    public function load($path, $type = null)
    {
        $dir = $this->locator->locate($path);

        $collection = new ServiceCollection();
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir), \RecursiveIteratorIterator::LEAVES_ONLY) as $file) {
            if (!$file->isFile() || '.php' !== substr($file->getFilename(), -4)) {
                continue;
            }
            $collection->addCollection($this->_annotationLoader->load($file->getPathName()));
        }

        return $collection;
    }

    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed  $resource A resource
     * @param string $type     The resource type
     *
     * @return boolean True if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        try {
            $path = $this->locator->locate($resource);
        } catch (\Exception $e) {
            return false;
        }

        return is_string($resource) && is_dir($path) && (!$type || 'annotation' === $type);
    }
}
