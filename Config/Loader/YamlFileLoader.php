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

use Itart\Bundle\RpcBundle\Service\ServiceDeclaration;
use Itart\Bundle\RpcBundle\Service\ServiceCollection;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Loader\FileLoader;

/**
 * YamlFileLoader loads Yaml RPC services files.
 *
 * @package    ItartRpcBundle
 * @subpackage Config
 * @author     Pavel Gopanenko <pavelgopanenko@gmail.com>
 * @copyright  2012 Pavel Gopanenko
 */
class YamlFileLoader extends FileLoader
{
    private static $availableKeys = array(
        'service', 'methods', 'type', 'resource', 'protocols', 'namespace'
    );

    /**
     * Loads a Yaml file.
     *
     * @param string $file A Yaml file path
     * @param string $type The resource type
     *
     * @return ServiceCollection A ServiceCollection instance
     *
     * @throws \InvalidArgumentException When service declaration can't be parsed
     *
     * @api
     */
    public function load($file, $type = null)
    {
        $path = $this->locator->locate($file);

        $config = Yaml::parse($path);

        $collection = new ServiceCollection();

        // empty file
        if (null === $config) {
            $config = array();
        }

        // not an array
        if (!is_array($config)) {
            throw new \InvalidArgumentException(sprintf('The file "%s" must contain a YAML array.', $file));
        }

        foreach ($config as $name => $config) {
            $config = $this->normalizeRpcConfig($config);

            if (isset($config['resource'])) {
                $type = isset($config['type']) ? $config['type'] : null;
                $this->setCurrentDir(dirname($path));
                $collection->addCollection($this->import($config['resource'], $type, false, $file));
            } else {
                $this->parseServiceDeclaration($collection, $name, $config);
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
     *
     * @api
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION) && (!$type || 'yaml' === $type);
    }

    /**
     * Parses a service declaration and adds it to the ServiceCollection.
     *
     * @param RouteCollection $collection A ServiceCollection instance
     * @param string          $name       Service name
     * @param array           $config     RPC Service definition
     *
     * @throws \RuntimeException When config pattern is not defined for the given service definition
     */
    protected function parseServiceDeclaration(ServiceCollection $collection, $name, $config)
    {
        if (!$config['service']) {
            throw new \RuntimeException('RPC declaration mus contains "service" id.');
        }

        $collection->add($name, new ServiceDeclaration($config));
    }

    /**
     * Normalize service definition configuration.
     *
     * @param array $config A resource config
     *
     * @return array
     *
     * @throws \InvalidArgumentException if one of the provided config keys is not supported
     */
    private function normalizeRpcConfig(array $config)
    {
        foreach ($config as $key => $value) {
            if (!in_array($key, self::$availableKeys)) {
                throw new \InvalidArgumentException(sprintf(
                    'Yaml web-service loader does not support given key: "%s". Expected one of the (%s).',
                    $key, implode(', ', self::$availableKeys)
                ));
            }
        }

        return $config;
    }
}
