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

use Symfony\Component\Config\Exception\FileLoaderLoadException;
use Symfony\Component\Config\Loader\DelegatingLoader as BaseDelegatingLoader;

/**
 * DelegatingLoader delegates service collection loading to other loaders using a loader resolver.
 *
 * @package    ItartRpcBundle
 * @subpackage Config
 * @author     Pavel Gopanenko <pavelgopanenko@gmail.com>
 * @copyright  2012 Pavel Gopanenko
 */
class DelegatingLoader extends BaseDelegatingLoader
{
    /**
     * Loads a resource.
     *
     * @param mixed  $resource A resource
     * @param string $type     The resource type
     *
     * @return RouteCollection A ServiceCollection instance
     *
     * @throws FileLoaderLoadException Thrown if resolver no find loader
     */
    public function load($resource, $type = null)
    {
        if (false === $loader = $this->resolver->resolve($resource, $type)) {
            throw new FileLoaderLoadException($resource);
        }

        return $loader->load($resource, $type);
    }
}
