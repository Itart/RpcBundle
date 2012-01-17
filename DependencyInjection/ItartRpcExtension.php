<?php

/*
 * This file is part of the RPC Bundle for Symfony2.
 *
 * (c) Pavel Gopanenko <pavelgopanenko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itart\Bundle\RpcBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Load RPC extension features.
 *
 * @package    ItartRpcBundle
 * @subpackage DependencyInjection
 * @author     Pavel Gopanenko <pavelgopanenko@gmail.com>
 * @copyright  2012 Pavel Gopanenko
 */
class ItartRpcExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array            $config    An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @see Symfony\Component\DependencyInjection\Extension.ExtensionInterface::load()
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('rpc.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $config);

        $container->setParameter('rpc.resource', $config['resource']);

        $this->addClassesToCompile(array(
            'Itart\Bundle\RpcBundle\Config\Loader\DelegatingLoader',
            'Itart\Bundle\RpcBundle\Config\Loader\YamlFileLoader',
            'Itart\Bundle\RpcBundle\Config\Loader\AnnotationDirectoryLoader',
            'Itart\Bundle\RpcBundle\Server\Builder\ServerBuilder',
        ));
    }
}
