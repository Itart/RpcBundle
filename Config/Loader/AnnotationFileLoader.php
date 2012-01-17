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

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;
use Itart\Bundle\RpcBundle\Service\ServiceCollection;
use Itart\Bundle\RpcBundle\Service\ServiceDeclaration;
use Itart\Bundle\RpcBundle\Config\Annotation\Service as ServiceAnnotation;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * AnnotationFileLoader loads service information from annotations set
 * on a PHP class and its methods.
 *
 * @package    ItartRpcBundle
 * @subpackage Config
 * @author     Pavel Gopanenko <pavelgopanenko@gmail.com>
 * @copyright  2012 Pavel Gopanenko
 */
class AnnotationFileLoader extends FileLoader
{
    private $_reader;

    /**
     * Constructor.
     *
     * @param FileLocator $locator A FileLocator instance
     * @param Reader      $reader  An Reader instance
     */
    public function __construct(FileLocator $locator, Reader $reader)
    {
        if (!function_exists('token_get_all')) {
            throw new \RuntimeException('The Tokenizer extension is required for the routing annotation loaders.');
        }

        parent::__construct($locator);

        AnnotationRegistry::registerFile(__DIR__ . "/../Annotation/RpcAnnotations.php");
        $this->_reader = $reader;
    }

    /**
     * Loads from annotations from a file.
     *
     * @param string $file A PHP file path
     * @param string $type The resource type
     *
     * @return ServiceCollection A ServiceCollection instance
     */
    public function load($file, $type = null)
    {
        $path = $this->locator->locate($file);

        $collection = new ServiceCollection();
        if ($class = $this->findClass($path)) {

            $annotations = $this->_reader->getClassAnnotations(new \ReflectionClass($class));
            while ($annotations) {
                $annotation = array_shift($annotations);
                if ($annotation instanceof ServiceAnnotation) {
                    $this->parseServiceAnnotation($collection, $class, $annotation);
                }
            }
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
        return is_string($resource) && 'php' === pathinfo($resource, PATHINFO_EXTENSION) && (!$type || 'annotation' === $type);
    }

    /**
     * Parse service annotation to service collection.
     *
     * @param ServiceCollection $collection A ServiceCollection instance
     * @param string            $class      Class name
     * @param ServiceAnnotation $annotation A ServiceAnnotation instance
     */
    protected function parseServiceAnnotation(ServiceCollection $collection, $class, ServiceAnnotation $annotation)
    {
        $config = array(
            'protocols' => $annotation->protocols ? explode(',', $annotation->protocols) : null,
            'namespace' => $annotation->namespace,
            'service'   => $annotation->service,
            'class'     => $class,
        );
        $name = isset($config['service']) ? $config['service'] : $config['class'];

        $collection->add($name, new ServiceDeclaration($config));
    }

    /**
     * Returns the full class name for the first class in the file.
     *
     * @param string $file A PHP file path
     *
     * @return string|false Full class name if found, false otherwise
     */
    protected function findClass($file)
    {
        $class = false;
        $namespace = false;
        $tokens = token_get_all(file_get_contents($file));
        for ($i = 0, $count = count($tokens); $i < $count; $i++) {
            $token = $tokens[$i];

            if (!is_array($token)) {
                continue;
            }

            if (true === $class && T_STRING === $token[0]) {
                return $namespace.'\\'.$token[1];
            }

            if (true === $namespace && T_STRING === $token[0]) {
                $namespace = '';
                do {
                    $namespace .= $token[1];
                    $token = $tokens[++$i];
                } while ($i < $count && is_array($token) && in_array($token[0], array(T_NS_SEPARATOR, T_STRING)));
            }

            if (T_CLASS === $token[0]) {
                $class = true;
            }

            if (T_NAMESPACE === $token[0]) {
                $namespace = true;
            }
        }

        return false;
    }
}
